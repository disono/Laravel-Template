<?php
/**
 * @author          Archie, Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (webmons.com), 2016-2018
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Requests\Admin\Application\Role;

use App\Http\Requests\Admin\AdminRequest;

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
            'slug' => 'required|max:100|alpha_dash|unique:roles,slug,' . request('id'),
            'description' => 'max:100'
        ];
    }
}
