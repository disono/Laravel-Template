<?php

namespace App\Http\Requests\Module\Chat;

use App\Http\Requests\Module\ModuleRequest;

class SendMessage extends ModuleRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'chat_group_id' => 'required|integer|exists:chat_groups,id',
            'message' => 'required'
        ];
    }
}
