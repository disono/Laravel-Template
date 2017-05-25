<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */

namespace App\Http\Requests\Admin;

class PageCategoryUpdate extends AdminRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|integer|exists:page_categories,id',
            'name' => 'required|max:100',
            'slug' => 'required|max:100|alpha_dash|not_in:' . exclude_slug() . '|unique:slugs,name,' . $this->input('id') . ',source_id',
            'parent_id' => 'integer|exists:page_categories,id',
            'description' => 'max:500'
        ];
    }
}
