<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Requests\Admin;

class RoleUpdate extends AdminRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|integer|exists:roles,id',
            'name' => 'required|max:100',
            'slug' => 'required|max:100|alpha_dash|unique:roles,slug,' . $this->get('id'),
            'description' => 'max:500'
        ];
    }
}
