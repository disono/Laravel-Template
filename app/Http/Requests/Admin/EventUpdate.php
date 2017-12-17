<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
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
