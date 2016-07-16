<?php

namespace App\Http\Requests\Web;

use App\Http\Requests\Request;

class UserUpdatePassword extends Request
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
            'id' => 'required|integer|exists:users,id',
            'email' => 'required|email',
            'password' => 'required|max:32|password_complex'
        ];
    }
}
