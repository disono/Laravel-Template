<?php
/**
 * @author          Archie, Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (webmons.com), 2016-2018
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Requests\Admin\User;

use App\Http\Requests\Admin\AdminRequest;

class UserStore extends AdminRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'middle_name' => 'max:100',
            'gender' => 'required|in:Male,Female',
            'birthday' => 'date',
            'address' => 'required|max:500',
            'postal_code' => 'required|numeric',
            'country_id' => 'exists:countries,id',
            'city_id' => 'exists:cities,id',
            'phone' => 'numeric',
            'profile_picture' => 'image',
            'role_id' => 'required|exists:roles,id',
            'username' => 'required|max:64|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|max:32',
            'is_email_verified' => 'in:1,0',
            'is_phone_verified' => 'in:1,0',
            'is_account_activated' => 'in:1,0',
            'is_account_enabled' => 'in:1,0'
        ];
    }
}
