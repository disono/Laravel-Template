<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */
namespace App\Http\Controllers\Web\Authentication\Social;

use App\Http\Controllers\Controller;
use App\Models\AuthHistory;
use App\Models\Slug;
use App\Models\SocialAuth;
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
        $this->middleware('guest', ['except' => [
            'facebook', 'facebookCallback'
        ]]);

        // check if facebook auth is enabled
        if (app_settings('auth_social_facebook')->value != 'enabled') {
            abort(404);
        }
    }

    /**
     * Facebook redirect
     *
     * @return mixed
     */
    public function facebook()
    {
        return Socialite::driver('facebook')->fields([
            'name', 'first_name', 'last_name', 'email', 'gender', 'birthday'
        ])->scopes([
            'email', 'user_birthday'
        ])->redirect();
    }

    /**
     * Facebook callback
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function facebookCallback()
    {
        try {
            $user = Socialite::driver('facebook')->fields([
                'name', 'first_name', 'last_name', 'email', 'gender', 'birthday'
            ])->user();
        } catch (\Exception $e) {
            return redirect('404');
        }

        $user_query = null;
        if ($user->getId()) {
            $user_query = SocialAuth::where('identifier', $user->getId())->first();
        }

        // check for api authentication
        if (!$user_query) {
            return $this->_createUser($user);
        } else {
            // login the user
            Auth::loginUsingId($user_query->user_id);
            $this->_logAuthentication();

            return redirect('dashboard');
        }
    }

    /**
     * Log the authentication
     */
    private function _logAuthentication()
    {
        // authentication history
        if (auth()->check()) {
            AuthHistory::store([
                'user_id' => auth()->user()->id,
                'ip' => get_ip_address(),
                'platform' => get_user_agent(),
                'type' => 'login'
            ]);
        }
    }

    /**
     * Social auth does not require email verification
     *
     * @param $user
     * @return bool|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    private function _createUser($user)
    {
        $create = User::create([
            'first_name' => ucfirst($user->user['first_name']),
            'last_name' => ucfirst($user->user['last_name']),
            'email' => ($user->user['email']) ? $user->user['email'] : '',
            'role' => 'client',
            'enabled' => 1,
            'email_confirmed' => 1
        ]);

        // create user
        if ($create) {
            // social auth
            SocialAuth::insert([
                'user_id' => $create->id,
                'identifier' => $user->getId(),
                'created_at' => sql_date()
            ]);

            // username
            $username = ($user->getNickname()) ? $user->getNickname() : preg_replace('/\s+/', '', $user->getNickname()) . time();
            Slug::store([
                'source_id' => $create->id,
                'source_type' => 'user',
                'name' => $username
            ]);

            // login the user
            Auth::loginUsingId($create->id);

            $this->_logAuthentication();
            return redirect('dashboard');
        }

        return redirect('login');
    }
}
