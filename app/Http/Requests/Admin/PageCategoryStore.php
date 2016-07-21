<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class PageCategoryStore extends Request
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
            'slug' => 'required|max:100|alpha_dash|not_in:' . exclude_slug() . '|unique:page_categories,slug',
            'description' => 'max:500'
        ];
    }
}
