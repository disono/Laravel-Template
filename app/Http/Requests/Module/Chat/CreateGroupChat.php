<?php

namespace App\Http\Requests\Module\Chat;

use Illuminate\Foundation\Http\FormRequest;

class CreateGroupChat extends FormRequest
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
            'name' => 'required|alpha_dash_spaces|max:100'
        ];
    }
}
