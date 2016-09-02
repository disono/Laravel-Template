<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */
namespace App\Http\Controllers\API\V1\Authenticate;

use App\Events\EventSignUp;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Slug;
use App\Models\SocialAuth;
use App\Models\User;

class RegisterController extends Controller
{
    /**
     * Create new user
     *
     * @param Requests\API\V1\AuthRegister $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Requests\API\V1\AuthRegister $request)
    {
        $request = $request->all();

        $user = null;
        if (isset($request['social_id'])) {
            $user = SocialAuth::where('identifier', $request['social_id'])->first();
        }

        if (!$user) {
            $create = User::create([
                'first_name' => ucfirst($request['first_name']),
                'last_name' => ucfirst($request['last_name']),

                'phone' => (isset($request['phone'])) ? $request['phone'] : '',
                'email' => (isset($request['email'])) ? $request['email'] : '',
                'password' => (isset($request['password'])) ? bcrypt($request['password']) : '',

                'role' => 'client',
                'enabled' => 1,
                'email_confirmed' => ((isset($request['social_id'])) ? 1 : 0)
            ]);

            if ($create) {
                Slug::store([
                    'source_id' => $create->id,
                    'source_type' => 'user',
                    'name' => (isset($request['social_id'])) ? $create->id . time() . str_random() : $request['username']
                ]);

                // send email for email verification if not authenticated by social
                if (!isset($request['social_id'])) {
                    event(new EventSignUp([
                        'user' => $create
                    ]));
                }
            } else {
                return failed_json_response('Can not create user.');
            }

            // save auth id
            if (isset($request['social_id'])) {
                SocialAuth::insert([
                    'user_id' => $create->id,
                    'identifier' => $request['social_id']
                ]);
            }

            $user_id = $create->id;
        } else {
            $user_id = $user->user_id;
        }

        return success_json_response(User::single($user_id));
    }
}
