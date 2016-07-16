<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: http://www.webmons.com
 * License: Apache 2.0
 */

namespace App\Http\Controllers\Web\Authentication\Social;

use App\SocialAuth;
use App\User;

use App\Http\Requests;
use App\Http\Controllers\Controller;
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
        $user = Socialite::driver('facebook')
            ->user();

        $user_query = null;
        if ($user->getId()) {
            $user_query = SocialAuth::where('identifier', $user->getId())->first();
        }

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

            if ($create) {
                SocialAuth::insert([
                    'user_id' => $create->id,
                    'identifier' => $user->getId()
                ]);

                // login the user
                Auth::loginUsingId($create->id);

                return redirect('dashboard');
            }
        } else {
            // login the user
            Auth::loginUsingId($user_query->user_id);

            return redirect('dashboard');
        }
        
        return abort(404);
    }
}
