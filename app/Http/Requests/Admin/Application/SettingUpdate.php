<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Requests\Admin\Application;

use App\Http\Requests\Admin\AdminRequest;

class SettingUpdate extends AdminRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|integer|exists:settings,id',
            'name' => 'required|max:100',
            'key' => 'required|alpha_dash|unique:settings,key,' . request('id'),
            'input_type' => 'required|in:text,select,checkbox_multiple,checkbox_single',
            'is_disabled' => 'in:0,1',
            'category_setting_id' => 'required|exists:setting_categories,id'
        ];
    }
}
