<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */
namespace App\Http\Requests\Admin;

class PageUpdate extends AdminRequest
{
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
            'slug' => 'required|max:100|alpha_dash|not_in:' . exclude_slug() . '|unique:slugs,name,' . $this->get('id') . ',source_id',
            'content' => 'max:50000',

            'page_category_id' => 'required|integer|exists:page_categories,id',
            'template' => 'max:100',

            'draft' => 'integer',

            'image' => 'image|max:' . config_file_size(),
        ];
    }
}
