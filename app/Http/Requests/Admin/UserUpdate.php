<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Requests\Admin;

class UserUpdate extends AdminRequest
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
            'phone' => 'required|numeric|digits_between:7,22',
            'birthday' => 'required|date|birthday:' . config_min_age(),
            'image' => 'image|max:' . config_file_size(),

            'username' => 'required|max:32|alpha_dash|unique:slugs,name,' . $this->get('id') . ',source_id',
            'email' => 'required|email|max:100|unique:users,email,' . $this->get('id'), ',email',
            'password' => 'password_complex',
            'role' => 'required|exists:roles,slug',

            'email_confirmed' => 'integer',
            'address' => 'required|max:500',
        ];
    }
}
