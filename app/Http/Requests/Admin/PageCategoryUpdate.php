<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
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
