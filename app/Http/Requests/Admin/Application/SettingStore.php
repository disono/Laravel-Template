<?php
/**
 * @author          Archie, Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (webmons.com), 2016-2018
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Requests\Admin\Application;

use App\Http\Requests\Admin\AdminRequest;

class SettingStore extends AdminRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:100',
            'key' => 'required|alpha_dash|unique:settings,key',
            'input_type' => 'required|in:text,select,checkbox',
            'is_disabled' => 'in:0,1'
        ];
    }
}
