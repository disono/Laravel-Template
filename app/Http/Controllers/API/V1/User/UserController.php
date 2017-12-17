<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\Controller;
use App\Models\FcmToken;
use App\Models\Setting;
use App\Models\User;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    /**
     * List of users
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return success_json_response(User::get(request_options([
            'search', 'role'
        ])));
    }

    /**
     * Get user information
     *
     * @param $id
     * @return array
     */
    public function getShow($id)
    {
        $user = User::single($id);
        if (!$user) {
            return failed_json_response(exception_messages('USER_NOT_FOUND'));
        }

        // if user is syncing add the application settings
        if (authenticated_id() == $id) {
            $user->application_settings = Setting::getAll();
        }

        return success_json_response($user);
    }

    /**
     * Create new token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postTokenCreate()
    {
        try {
            $user = User::find(authenticated_id());

            // too bac user not found
            if (!$user) {
                return failed_json_response('No user found!');
            }

            $token = JWTAuth::fromUser($user);
        } catch (JWTException $e) {
            return failed_json_response($e->getMessage());
        }

        // all good so return the token
        return success_json_response(compact('token'));
    }

    /**
     * Check if the token is valid
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postTokenCheck()
    {
        try {
            // this will set the token on the object
            JWTAuth::parseToken();

            // and you can continue to chain methods
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return failed_json_response('No user found!');
            }

            return success_json_response(User::single($user->id));
        } catch (JWTException $e) {
            return failed_json_response($e->getMessage());
        }
    }

    /**
     * FCM token
     *
     * @param $id
     * @param $token
     * @return \Illuminate\Http\JsonResponse
     */
    public function FCMToken($id, $token)
    {
        FcmToken::add($id, $token);

        return success_json_response(User::single($id));
    }
}
