<?php

namespace App\Http\Requests\Admin;

class UserCreate extends AdminRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'phone' => 'required|numeric|digits_between:7,22',
            'birthday' => 'required|date|birthday:' . config_min_age(),
            'image' => 'image|max:' . config_file_size(),

            'username' => 'required|max:32|alpha_dash|unique:slugs,name',
            'email' => 'required|email|max:100|unique:users',
            'password' => 'required|password_complex',
            'role' => 'required|exists:roles,slug',

            'email_confirmed' => 'integer',
            'address' => 'required|max:500',
        ];
    }
}
