<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */

namespace App\Http\Requests\Admin;

class EventUpdate extends AdminRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|integer|exists:events,id',

            'name' => 'required|max:100',
            'slug' => 'required|max:100|alpha_dash|not_in:' . exclude_slug() . '|unique:events,slug,' . $this->get('id'),
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
