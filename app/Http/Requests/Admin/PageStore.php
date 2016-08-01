<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class PageStore extends Request
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
            'slug' => 'required|max:100|alpha_dash|not_in:' . exclude_slug() . '|unique:slugs,name,null,source_id,source_type,page',
            'content' => 'max:50000',

            'page_category_id' => 'required|integer|exists:page_categories,id',
            'template' => 'max:100',

            'draft' => 'integer',
            
            'image' => 'image|max:' . config_file_size(),
        ];
    }
}
