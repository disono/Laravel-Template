<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */

namespace App\Http\Requests\API\V1;

use App\Http\Requests\Request;


class RequestGuestAPI extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get all errors if validation failed
     *
     * @param array $errors
     * @return response
     */
    public function response(array $errors)
    {
        return failed_json_response($errors);
    }

    /**
     * Invalid credentials
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function forbiddenResponse()
    {
        return failed_json_response(env('MSG_ACCESS'), 401);
    }
}