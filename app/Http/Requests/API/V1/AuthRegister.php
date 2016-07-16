<?php

namespace App\Http\Requests\API\V1;

use App\Http\Requests\Request;

class AuthRegister extends Request
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
        $social_id = $this->input('social_id');

        return [
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'phone' => 'required_if:social_id,0|numeric|digits_between:7,22',
            'username' => 'required_if:social_id,0|max:32|alpha_dash|unique:users,username',
            'email' => 'required_if:social_id,0|email' . (($social_id) ? '' : '|unique:users,email'),
            'password' => 'required_if:social_id,0|min:4|max:32',
            'social_id' => 'numeric'
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
