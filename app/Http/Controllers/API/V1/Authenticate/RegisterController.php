<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: http://www.webmons.com
 * License: Apache 2.0
 */

namespace App\Http\Controllers\API\V1\Authenticate;

use App\Events\EventSignUp;
use App\SocialAuth;
use App\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;

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
        if ($request['social_id']) {
            $user = SocialAuth::where('identifier', $request['social_id'])->first();
        }

        if (!$user) {
            $create = User::create([
                'first_name' => ucfirst($request['first_name']),
                'last_name' => ucfirst($request['last_name']),
                'username' => (isset($request['username'])) ? $request['username'] : str_random(4) . time(),
                'phone' => (isset($request['phone'])) ? $request['phone'] : '',
                'email' => (isset($request['email'])) ? $request['email'] : '',
                'password' => (isset($request['password'])) ? bcrypt($request['password']) : '',
                'role' => 'client',
                'enabled' => 1,
                'email_confirmed' => (($request['social_id']) ? 1 : 0)
            ]);

            if ($create) {
                // send email for email verification
                event(new EventSignUp([
                    'user' => $create
                ]));
            } else {
                return failed_json_response('Can not create user.');
            }

            // save auth id
            if ($request['social_id']) {
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
