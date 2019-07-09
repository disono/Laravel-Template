<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models;

use App\Models\Vendor\BaseUser;
use App\Models\Vendor\Facades\AuthenticationHistory;
use App\Models\Vendor\Facades\File;
use App\Models\Vendor\Facades\FirebaseNotification;
use App\Models\Vendor\Facades\Page;
use App\Models\Vendor\Facades\PageView;
use App\Models\Vendor\Facades\Token;
use App\Models\Vendor\Facades\UserAddress;
use App\Models\Vendor\Facades\UserPhone;
use App\Models\Vendor\Facades\Verification;
use App\Models\Vendor\SMSIntegrator;
use App\Notifications\RegisterNotification;
use Exception;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class User extends BaseUser
{
    protected $tableName = 'users';
    protected $writableColumns = [
        'first_name', 'last_name', 'middle_name', 'gender',
        'birthday', 'address', 'postal_code', 'country_id', 'city_id',
        'phone', 'role_id',
        'username', 'email', 'password',
        'is_email_verified', 'is_phone_verified', 'is_account_activated', 'is_account_enabled',
        'active_at'
    ];

    protected $files = ['profile_picture'];
    protected $fileOptions = [
        'profile_picture' => ['tag' => 'profile_picture', 'width' => 360, 'height' => 360, 'remove_previous' => true]
    ];

    protected $inputDates = ['birthday'];
    protected $inputDateTimes = ['active_at'];
    protected $inputCrypt = ['password'];
    protected $inputBooleans = ['is_email_verified', 'is_phone_verified', 'is_account_activated', 'is_account_enabled'];

    protected $columnHasRelations = ['country_id', 'city_id', 'role_id'];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function __construct(array $attributes = [])
    {
        $this->fillable($this->writableColumns);
        parent::__construct($attributes);
    }

    public function actionRemoveBefore($results)
    {
        foreach ($results as $row) {
            Page::remove($row->id, 'user_id');
            Token::remove($row->id, 'user_id');
            UserAddress::remove($row->id, 'user_id');
            UserPhone::remove($row->id, 'user_id');
            Verification::remove($row->id, 'user_id');
            AuthenticationHistory::remove($row->id, 'user_id');
            FirebaseNotification::remove($row->id, 'user_id');
            PageView::remove($row->id, 'user_id');
        }

        return true;
    }

    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }

    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }

    public function authenticationHistory()
    {
        return $this->hasMany('App\Models\AuthenticationHistory');
    }

    public function page()
    {
        return $this->hasMany('App\Models\Page');
    }

    public function socialAuthentication()
    {
        return $this->hasMany('App\Models\SocialAuthentication');
    }

    public function token()
    {
        return $this->hasMany('App\Models\Token');
    }

    public function address()
    {
        return $this->hasMany('App\Models\UserAddress');
    }

    public function phone()
    {
        return $this->hasMany('App\Models\UserPhone');
    }

    public function verification()
    {
        return $this->hasMany('App\Models\Verification');
    }

    public function file()
    {
        return $this->hasMany('App\Models\File');
    }

    /**
     * Register new user
     *
     * @param array $data
     * @return null
     */
    public function register(array $data)
    {
        // create new user
        $user = self::create([
            'first_name' => ucfirst($data['first_name']),
            'last_name' => ucfirst($data['last_name']),

            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),

            'role_id' => 3,
            'is_account_enabled' => 1,
            'is_account_activated' => 1
        ]);

        try {
            $this->_resendEmail($user, TRUE);
        } catch (\Exception $e) {

        }

        return $this->single($user->id);
    }

    /**
     * Resend verification to email email
     *
     * @param $user
     * @param bool $reNew
     *
     * @throws Exception
     */
    private function _resendEmail($user, $reNew = TRUE)
    {
        $verification = Verification::where('value', $user->email)->where('type', 'email')->where('user_id', $user->id);
        $_verification = $verification->first();

        if ($reNew || !$_verification) {
            // new token
            $user->verification_code = str_random(64);

            // clean all verification before saving new
            $verification->delete();

            // create token
            Verification::create([
                'user_id' => $user->id,
                'token' => $user->verification_code,
                'value' => $user->email,
                'type' => 'email',
                'expired_at' => expiredAt(__settings('emailVerificationExpiration')->value)
            ]);
        } else {
            if ($verification->tries > 0 && $verification->tries > (int)__settings('emailVerificationThreshold')->value) {
                throwError('VERIFICATION_EMAIL_TRIES');
            }

            $user->verification_code = $verification->token;
        }

        // send email for email verification
        Notification::send($user, new RegisterNotification($user));
    }

    /**
     * Crate a token for user
     *
     * @param $user
     * @param string $source sources: mobile, client, server
     * @param null $token random alphanumeric
     * @param int $expiredAt expired in 15 days by default
     *
     * @return null
     */
    public function crateToken($user, $source = 'mobile', $token = NULL, $expiredAt = 21600)
    {
        if (!$user) {
            return NULL;
        }

        $token = $token ? $token : str_random(64);
        $user->server_timestamp = sqlDate();
        $user->token = Token::store([
            'user_id' => $user->id,
            'token' => $token,
            'key' => str_random(64),
            'secret' => str_random(64),
            'source' => $source,
            'expired_at' => expiredAt($expiredAt)
        ]);

        return $user;
    }

    /**
     * Verify phone or email
     *
     * @param $type
     * @return mixed
     * @throws Exception
     */
    public function verify($type)
    {
        // verifications
        $verification = Verification::where('token', request('token'))
            ->where('value', request($type))
            ->where('type', $type)
            ->first();

        // check if token is valid
        if (!$verification) {
            throwError('TOKEN_NOT_FOUND');
        }

        // check if token is not expired
        if (strtotime($verification->expired_at) <= time()) {
            throwError('TOKEN_IS_EXPIRED');
        }

        // check how many tries failed the verification (phone)
        if ($type === 'phone' && $verification->tries > 0 && $verification->tries > (int)__settings('phoneVerificationThreshold')->value) {
            throwError('VERIFICATION_PHONE_TRIES');
        }

        // check how many tries failed the verification (email)
        if ($type === 'email' && $verification->tries > 0 && $verification->tries > (int)__settings('emailVerificationThreshold')->value) {
            throwError('VERIFICATION_EMAIL_TRIES');
        }

        // get user
        $user = self::where($type, $verification->value)->first();
        if (!$user) {
            throwError('INVALID_RAW', $type . '.');
        }

        // update user is verified
        if ($type === 'email') {
            self::where('id', $user->id)->update(['is_email_verified' => 1]);
        } else if ($type === 'phone') {
            self::where('id', $user->id)->update(['is_phone_verified' => 1]);
        }

        // clean all verification token
        Verification::where('value', $user->$type)->where('type', $type)->delete();

        // login user
        Auth::loginUsingId($user->id);
        __initializeSettings();

        return $type;
    }

    /**
     * Resend verification for email and phone
     *
     * @param $type
     * @return array|Request|string
     * @throws Exception
     */
    public function resendVerification($type)
    {
        $value = request('type_value');

        // do we have a value to search for
        if (!$value) {
            throwError('RAW', $type . ' is required.');
        }

        // did the phone or email exists
        $user = User::where($type, $value)->first();
        if (!$user) {
            throwError('RAW', $type . ' is not registered.');
        }

        // did the old verification exists
        $verification = Verification::where('value', $value)->where('type', $type)->where('user_id', $user->id)->first();
        if ($verification) {
            if (strtotime($verification->expired_at) > time()) {
                // not expired
                $this->_resendCode($type, $user, FALSE);
            } else {
                // expired renew
                $this->_resendCode($type, $user, TRUE);
            }
        } else {
            $this->_resendCode($type, $user, TRUE);
        }

        return $value;
    }

    /**
     * Resend the verification code
     *
     * @param $type
     * @param $user
     * @param bool $reNew
     * @throws Exception
     */
    private function _resendCode($type, $user, $reNew = TRUE)
    {
        if ($type == 'phone') {
            $this->_resendPhone($user, $reNew);
        } else if ($type == 'email') {
            $this->_resendEmail($user, $reNew);
        }
    }

    /**
     * Resend verification to phone
     *
     * @param $user
     * @param bool $reNew
     * @throws Exception
     */
    private function _resendPhone($user, $reNew = TRUE)
    {
        $verification = Verification::where('value', $user->phone)->where('type', 'phone')->where('user_id', $user->id);
        $_verification = $verification->first();

        if ($reNew || !$_verification) {
            // clean all verification before saving new
            $verification->delete();
            $verification_code = ucwords(str_random(6));

            // create token
            Verification::create([
                'user_id' => $user->id,
                'token' => ucwords(str_random(6)),
                'value' => $user->phone,
                'type' => 'phone',
                'expired_at' => expiredAt(__settings('phoneVerificationExpiration')->value)
            ]);
        } else {
            if ($verification->tries > 0 && $verification->tries > (int)__settings('phoneVerificationThreshold')->value) {
                throwError('VERIFICATION_PHONE_TRIES');
            }

            $verification_code = $verification->token;
        }

        SMSIntegrator::send($user->phone, 'Your Verification Code: ' . $verification_code);
    }

    /**
     * Set the current time user last session
     */
    public function setActiveAt()
    {
        $this->clearBoolean()
            ->clearInteger()
            ->clearNumeric()
            ->edit(__me()->id, ['active_at' => sqlDate()], TRUE, FALSE);
    }

    protected function customQuerySelectList(): array
    {
        return [
            'full_name' => 'CONCAT(users.first_name, " ", users.last_name)',
            'role' => '(SELECT slug FROM roles WHERE users.role_id = roles.id LIMIT 1)',
            'country' => '(SELECT name FROM countries WHERE users.country_id = countries.id LIMIT 1)',
            'city' => '(SELECT name FROM cities WHERE users.city_id = cities.id LIMIT 1)',
            'is_online' => 'IF(DATE_ADD(users.active_at, INTERVAL 60 MINUTE) >= NOW(), 1, 0)',
            'is_latest' => 'IF(DATE_ADD(users.created_at, INTERVAL 10 DAY) >= CURDATE(), 1, 0)'
        ];
    }

    protected function dataFormatting($row)
    {
        $this->addDateFormatting($row);

        $row->profile_picture = $this->profilePicture($row->id, $row->gender);
        $row->birthday = ($row->birthday) ? humanDate($row->birthday, true) : NULL;
        $row->server_timestamp = sqlDate();

        // remove sensitive data
        $this->_unsetHidden($row);

        return $row;
    }

    /**
     * Get profile photo
     *
     * @param $id
     * @param string $gender
     *
     * @return UrlGenerator|string|null |null
     */
    public function profilePicture($id, $gender = 'male')
    {
        return File::lookForFile($id, 'users', 'profile_picture', iconPlaceholders($gender ? strtolower($gender) : 'male'))->primary;
    }

    /**
     * Unset hidden data
     *
     * @param $row
     */
    private function _unsetHidden($row)
    {
        foreach ($this->hidden as $item) {
            unset($row->$item);
        }
    }
}
