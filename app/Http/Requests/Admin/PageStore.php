<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Requests\Admin;

class PageStore extends AdminRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:100',
            'slug' => 'required|max:100|alpha_dash|not_in:' . exclude_slug() . '|unique:slugs,name',
            'content' => 'max:50000',

            'page_category_id' => 'required|integer|exists:page_categories,id',
            'template' => 'max:100',

            'draft' => 'integer',
            'is_email_to_subscriber' => 'integer',

            'image' => 'image|max:' . config_file_size(),
        ];
    }
}
