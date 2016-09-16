<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdate extends FormRequest
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
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'phone' => 'required|numeric|digits_between:7,22',
            'birthday' => 'required|date|birthday:' . config_min_age(),
            'image' => 'image|max:' . config_file_size(),

            'username' => 'required|max:32|alpha_dash|unique:slugs,name,' . $this->get('id') . ',source_id',
            'email' => 'required|email|max:100|unique:users,email,' . $this->get('id'), ',email',
            'password' => 'password_complex',
            'role' => 'required|exists:roles,slug',

            'email_confirmed' => 'integer',
            'address' => 'required|max:500',
        ];
    }
}
