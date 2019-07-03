<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Requests\Module\User;

use App\Http\Requests\Module\ModuleRequest;

class AccountSettings extends ModuleRequest
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
            'middle_name' => 'max:100',
            'last_name' => 'required|max:100',
            'profile_picture' => 'image|max:' . __settings('fileSizeLimitImage')->value,

            'address' => 'max:500',
            'phone' => 'between:7,22',
            'gender' => 'required|in:Male,Female',
            'birthday' => 'required|date|birthday:' . __settings('minimumAgeForRegistration')->value,

            'country_id' => 'integer|exists:countries,id',
            'city_id' => 'integer|exists:cities,id'
        ];
    }
}
