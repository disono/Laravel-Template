<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */
namespace App\Http\Controllers\API\V1\Authenticate;

use App\Http\Controllers\Controller;
use App\Http\Requests;
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
        if (auth()->once(['email' => $request->get('email'), 'password' => $request->get('password'),
            'email_confirmed' => 1, 'enabled' => 1])
        ) {

            return success_json_response(User::single($request->get('email'), 'email'));
        }

        return failed_json_response('Incorrect email or password.');
    }
}