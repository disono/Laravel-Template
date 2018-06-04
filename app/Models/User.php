<?php
/**
 * @author          Archie, Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (webmons.com), 2016-2018
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models;

use App\Models\Vendor\BaseUser;

class User extends BaseUser
{
    protected static $tableName = 'users';
    protected static $writableColumns = [
        'first_name', 'last_name', 'middle_name', 'gender',
        'birthday', 'address', 'postal_code', 'country_id', 'city_id',
        'phone', 'role_id',
        'username', 'email', 'password',
        'is_email_verified', 'is_phone_verified', 'is_account_activated', 'is_account_enabled'
    ];

    protected static $files = ['profile_picture'];
    protected static $imageOptions = ['tag' => 'profile_picture'];

    protected static $inputDates = ['birthday'];
    protected static $inputCrypt = ['password'];
    protected static $inputBooleans = ['is_email_verified', 'is_phone_verified', 'is_account_activated', 'is_account_enabled'];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function __construct(array $attributes = [])
    {
        $this->fillable(self::$writableColumns);
        parent::__construct($attributes);
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
     * Remove all boolean to update
     */
    public static function clearBoolean()
    {
        self::$inputBooleans = [];
    }

    /**
     * List of select
     *
     * @return array
     */
    protected static function rawQuerySelectList()
    {
        return [
            'full_name' => 'CONCAT(users.first_name, " ", users.last_name)',
            'role' => 'SELECT name FROM roles WHERE users.role_id = roles.id LIMIT 1',
            'country' => 'SELECT name FROM countries WHERE users.country_id = countries.id LIMIT 1',
            'city' => 'SELECT name FROM cities WHERE users.city_id = cities.id LIMIT 1',
        ];
    }

    /**
     * Add formatting to data
     *
     * @param $row
     * @return mixed
     */
    protected static function dataFormatting($row)
    {
        $row->profile_picture = fetchImage(self::profilePicture($row->id), 'assets/img/placeholders/profile_picture.png');
        $row->birthday = ($row->birthday) ? humanDate($row->birthday, true) : null;

        return $row;
    }

    /**
     * Get profile picture
     *
     * @param $user_id
     * @return null
     */
    private static function profilePicture($user_id)
    {
        $file = File::where('table_name', 'users')->where('table_id', $user_id)->where('tag', 'profile_picture')
            ->orderBy('created_at', 'DESC')->first();
        return ($file) ? $file->file_name : null;
    }

    /**
     * Remove any related data from user
     *
     * @param $query
     * @return bool
     */
    public static function actionRemove($query)
    {
        $user = $query->first();
        if (!$user) {
            return false;
        }

        Page::where('user_id', $user->id)->delete();
        Token::where('user_id', $user->id)->delete();
        UserAddress::where('user_id', $user->id)->delete();
        UserPhone::where('user_id', $user->id)->delete();
        Verification::where('user_id', $user->id)->delete();
        AuthenticationHistory::where('user_id', $user->id)->delete();

        return true;
    }
}
