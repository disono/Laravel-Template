<?php

namespace App\Http\Requests\Admin\Setting;

use App\Http\Requests\Admin\AdminRequest;

class CategoryStore extends AdminRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required'
        ];
    }
}
