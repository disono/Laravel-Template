<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */
namespace App\Http\Requests\Web;

class UserUpdateSettings extends WebRequest
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
