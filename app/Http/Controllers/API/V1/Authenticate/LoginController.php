<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */

namespace App\Http\Controllers\API\V1\Authenticate;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\AuthenticationToken;
use App\Models\AuthHistory;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Login user
     *
     * @param Requests\API\V1\AuthLogin $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Requests\API\V1\AuthLogin $request)
    {
        if (auth()->once(['email' => $request->get('email'), 'password' => $request->get('password')])
        ) {
            $user = User::single($request->get('email'), 'email');

            if ($user) {
                // check if user email is confirmed
                if (!$user->email_confirmed) {
                    return failed_json_response('Your email is not confirmed, please confirm your email.');
                }

                // check if user profile is enabled
                if (!$user->enabled) {
                    return failed_json_response('Your account is not enabled, please contact us.');
                }

                // create token
                $user = AuthenticationToken::createToken($user);

                if (!$user) {
                    return failed_json_response('Failed to create token.');
                } else {
                    // authentication history
                    AuthHistory::store([
                        'user_id' => $user->id,
                        'ip' => get_ip_address(),
                        'platform' => get_user_agent(),
                        'type' => 'login'
                    ]);
                }
            }

            return success_json_response($user);
        }

        return failed_json_response('Incorrect email or password.');
    }
}