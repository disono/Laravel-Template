<?php

namespace App\Http\Requests\Module\Chat;

use App\Http\Requests\Module\ModuleRequest;

class UpdateGroupChat extends ModuleRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|exists:chat_groups,id',
            'name' => 'required|alpha_dash_spaces|max:100'
        ];
    }
}
