<?php

namespace App\Http\Requests\API\V1;

use App\Http\Requests\Request;

class AuthLogin extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|max:100|exists:users,email',
            'password' => 'required|max:100'
        ];
    }

    /**
     * Get all errors if validation failed
     *
     * @param array $errors
     * @return response
     */
    public function response(array $errors)
    {
        return failed_json_response($errors);
    }
}
