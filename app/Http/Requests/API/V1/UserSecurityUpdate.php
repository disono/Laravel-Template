<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */
namespace App\Http\Requests\API\V1;

class UserSecurityUpdate extends RequestAuthAPI
{
    public function __construct()
    {
        parent::__construct();
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
            'current_password' => 'required|current_password',
            'password' => 'password_complex|confirmed'
        ];
    }
}
