<?php

namespace App\Http\Requests\Module\Chat;

use Illuminate\Foundation\Http\FormRequest;

class SendMessage extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return (__me()) ? true : false;
    }

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
