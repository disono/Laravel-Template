<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */
namespace App\Http\Requests\API\V1;

class AuthRegister extends RequestGuestAPI
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $social_id = $this->input('social_id');

        return [
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',

            'phone' => 'required_without:social_id|numeric|digits_between:7,22',
            'username' => 'required_without:social_id|max:32|alpha_dash|unique:slugs,name,null,source_id|not_in:' . exclude_slug(),
            'email' => 'required_without:social_id|email' . (($social_id) ? '' : '|unique:users,email'),
            'password' => 'required_without:social_id|min:4|max:32',
            'address' => 'max:1000',

            'social_id' => 'numeric'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'phone.required_without' => 'The phone number field is required!',
            'username.required_without' => 'The username field is required!',
            'email.required_without' => 'The email field is required!',
            'password.required_without' => 'The password field is required!',
        ];
    }
}
