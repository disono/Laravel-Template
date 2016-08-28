<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */
namespace App\Http\Requests\API\V1;

use App\Http\Requests\Request;

class PasswordRecovery extends Request
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|exists:users,email'
        ];
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
}
