<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */

namespace App\Http\Requests\Admin;

class AuthorizationUpdate extends AdminRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|integer|exists:authorizations,id',
            'name' => 'required|max:100',
            'identifier' => 'required|max:100|alpha_dash|unique:authorizations,identifier,' . $this->get('id'),
            'description' => 'max:500'
        ];
    }
}
