<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class PageUpdate extends Request
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
            'id' => 'required|integer|exists:pages,id',
            'name' => 'required|max:100',
            'slug' => 'required|max:100|alpha_dash|not_in:' . exclude_slug() . '|unique:pages,slug,' . $this->get('id'),
            'content' => 'max:50000',

            'page_category_id' => 'required|integer|exists:page_categories,id',
            'template' => 'max:100',

            'start_date' => 'date',
            'start_time' => 'date_format:h:i A',
            'end_date' => 'date',
            'end_time' => 'date_format:h:i A',

            'image' => 'image|max:' . config_file_size(),
        ];
    }
}
