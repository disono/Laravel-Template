<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */
namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    /**
     * Get user information
     *
     * @param $id
     * @return array
     */
    public function getShow($id)
    {
        return success_json_response(User::single($id));
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
}
