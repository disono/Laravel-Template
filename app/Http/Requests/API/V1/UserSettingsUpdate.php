<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */
namespace App\Http\Requests\API\V1;

use App\Http\Requests\Request;

class UserSettingsUpdate extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return api_auth();
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
