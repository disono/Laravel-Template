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
    }

    /**
     * Facebook redirect
     *
     * @return mixed
     */
    public function facebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Facebook callback
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function facebookCallback()
    {
        $user = Socialite::driver('facebook')->user();

        $user_query = null;
        if ($user->getId()) {
            $user_query = SocialAuth::where('identifier', $user->getId())->first();
        }

        // check for api authentication
        if (!$user_query) {
            $create = User::create([
                'first_name' => ucfirst($user->user['first_name']),
                'last_name' => ucfirst($user->user['last_name']),
                'username' => ($user->getNickname()) ? $user->getNickname() : preg_replace('/\s+/', '', $user->getNickname()) . time(),
                'email' => ($user->user['email']) ? $user->user['email'] : '',
                'role' => 'client',
                'enabled' => 1,
                'email_confirmed' => 1
            ]);

            // create user
            if ($create) {
                SocialAuth::insert([
                    'user_id' => $create->id,
                    'identifier' => $user->getId()
                ]);

                // login the user
                Auth::loginUsingId($create->id);

                $this->_logAuthentication();
                return redirect('dashboard');
            }
        } else {
            // login the user
            Auth::loginUsingId($user_query->user_id);

            $this->_logAuthentication();
            return redirect('dashboard');
        }

        return abort(404);
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
}
