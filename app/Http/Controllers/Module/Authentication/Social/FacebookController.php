<?php
/**
 * @author          Archie, Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (webmons.com), 2016-2018
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Module\Authentication\Social;

use App\Http\Controllers\Controller;
use App\Models\AuthenticationHistory;
use App\Models\File;
use App\Models\SocialAuthentication;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class FacebookController extends Controller
{
    /**
     * Create a new authentication controller instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('guest', ['except' => [
            'facebookAction', 'facebookCallbackAction'
        ]]);

        // check if facebook auth is enabled
        if (__settings('authSocialFacebook')->value != 'enabled') {
            abort(404);
        }
    }

    /**
     * Facebook redirect
     *
     * @return mixed
     */
    public function facebookAction()
    {
        if (__settings('authSocialFacebook')->value != 'enabled' ||
            __settings('authUserRegistration')->value != 'enabled') {
            abort(404);
        }

        return Socialite::driver('facebook')->fields([
            'name', 'first_name', 'last_name', 'email', 'gender', 'birthday'
        ])->scopes([
            'email', 'user_birthday'
        ])->redirect();
    }

    /**
     * Facebook callback
     *
     * @return bool
     */
    public function facebookCallbackAction()
    {
        try {
            $user = Socialite::driver('facebook')->fields([
                'name', 'first_name', 'last_name', 'email', 'gender', 'birthday'
            ])->user();
        } catch (\Exception $e) {
            return redirect('404');
        }

        // is social details exists
        $social = null;
        if ($user->getId()) {
            $social = SocialAuthentication::where('social_id', $user->getId())->where('type', 'Facebook')->first();
        }

        // check for api authentication
        if (!$social) {
            return $this->_createUser($user);
        }

        // login the user
        Auth::loginUsingId($social->user_id);
        initialize_settings();
        $this->_logAuthentication();

        return $this->redirect('dashboard');
    }

    /**
     * Social auth does not require email verification
     *
     * @param $user
     * @return bool|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    private function _createUser($user)
    {
        // let's create a temporary email
        $email = ($user->user['email']) ? $user->user['email'] :
            urlTitle(__settings('title')->value, '.') . time() . str_random(4) . '@' . env('APP_DOMAIN');
        $username = ($user->getNickname()) ? $user->getNickname() . time() : preg_replace('/\s+/', '', $user->getNickname()) . time();

        $create = User::store([
            'first_name' => ucfirst($user->user['first_name']),
            'last_name' => ucfirst($user->user['last_name']),

            'email' => $email,
            'username' => $username,
            'password' => bcrypt(str_random()),

            'role_id' => 3,
            'is_email_verified' => 1,
            'is_account_enabled' => 1,
            'is_account_activated' => 1
        ]);

        // create user
        if ($create) {
            // social auth
            SocialAuthentication::insert([
                'user_id' => $create->id,
                'social_id' => $user->getId(),
                'type' => 'Facebook',
                'created_at' => sqlDate()
            ]);

            // login the user
            Auth::loginUsingId($create->id);
            initialize_settings();

            // user's Facebook avatar
            $this->_downloadAvatar($user->getId());

            // log to history
            $this->_logAuthentication();

            return $this->redirect('dashboard');
        }

        return $this->redirect('login');
    }

    /**
     * Download avatar
     *
     * @param $social_id
     */
    private function _downloadAvatar($social_id)
    {
        if (!__me()) {
            return;
        }

        $filename = httpDownloadImage($social_id . '-' . time() . '-' . str_random(),
            'http://graph.facebook.com/' . $social_id . '/picture?type=large');

        if (!$filename) {
            File::store([
                'user_id' => __me()->id,
                'file_name' => $filename,
                'type' => 'photo',
                'title' => __me()->first_name,
                'table_name' => 'users',
                'table_id' => __me()->id,
                'tag' => 'profile'
            ]);
        }
    }

    /**
     * Log the authentication
     */
    private function _logAuthentication()
    {
        // authentication history
        if (__me()) {
            $userAgent = userAgent();
            AuthenticationHistory::store([
                'user_id' => __me()->id,
                'ip' => ipAddress(),
                'platform' => $userAgent->platform . ', ' . $userAgent->browserName,
                'type' => 'login'
            ]);
        }
    }
}
