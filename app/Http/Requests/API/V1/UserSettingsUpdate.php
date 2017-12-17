<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Requests\API\V1;

class UserSettingsUpdate extends RequestAuthAPI
{
    public function __construct()
    {
        parent::__construct();
    }

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
            'image' => 'image|max:' . config_file_size(),

            'address' => 'max:500',
            'about' => 'max:500',
            'phone' => 'between:7,22',

            'gender' => 'in:Male,Female',
            'birthday' => 'date|birthday:' . config_min_age(),

            'country_id' => 'integer|exists:countries,id'
        ];
    }
}
