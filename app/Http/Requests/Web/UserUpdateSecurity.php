<?php

namespace App\Http\Requests\Web;

use App\Http\Requests\Request;

class UserUpdateSecurity extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|unique:users,email,' . auth()->user()->id,
            'current_password' => 'current_password',
            'password' => 'required_with:current_password|password_complex|confirmed'
        ];
    }
}
