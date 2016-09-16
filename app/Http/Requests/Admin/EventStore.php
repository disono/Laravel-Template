<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */
namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class EventStore extends Request
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
            'name' => 'required|max:100',
            'slug' => 'required|max:100|alpha_dash|unique:events,slug|not_in:' . exclude_slug(),
            'content' => 'max:50000',
            'template' => 'max:100',

            'start_date' => 'date',
            'start_time' => 'date_format:h:i A',
            'end_date' => 'date',
            'end_time' => 'date_format:h:i A',

            'image' => 'image|max:' . config_file_size(),
        ];
    }
}
