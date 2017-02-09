<?php

namespace App\Http\Requests\Web\Auth;

use App\Http\Requests\Request;

class Register extends Request
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
            'username' => 'required|max:100|alpha_dash|unique:slugs,name|not_in:' . exclude_slug(),
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ];
    }
}
