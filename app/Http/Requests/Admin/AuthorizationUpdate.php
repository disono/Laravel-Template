<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class AuthorizationUpdate extends Request
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
            'id' => 'required|integer|exists:authorizations,id',
            'name' => 'required|max:100',
            'identifier' => 'required|max:100|alpha_dash|unique:authorizations,identifier,' . $this->get('id'),
            'description' => 'max:500'
        ];
    }
}