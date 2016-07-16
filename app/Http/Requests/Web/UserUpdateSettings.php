<?php

namespace App\Http\Requests\Web;

use App\Http\Requests\Request;

class UserUpdateSettings extends Request
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
            'image' => 'image|max:' . config_file_size(),

            'address' => 'max:500',
            'about' => 'max:500',
            'phone' => 'between:7,22',
            'gender' => 'required|in:Male,Female',
            'birthday' => 'required|date|birthday:' . config_min_age(),
            
            'country_id' => 'required|integer|exists:countries,id'
        ];
    }
}
