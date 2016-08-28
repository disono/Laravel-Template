<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */
namespace App\Http\Requests\API\V1;

use App\Http\Requests\Request;

class UserSecurityUpdate extends Request
{
    private $auth;

    public function __construct()
    {
        parent::__construct();

        $this->auth = api_auth();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->auth;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|unique:users,email' . (($this->auth) ? ',' . $this->auth->id : null),
            'current_password' => 'current_password',
            'password' => 'required_with:current_password|password_complex|confirmed'
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
