<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */
namespace App\Http\Requests\Admin;

use App\Models\Setting;

class SettingUpdate extends AdminRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $inputs = [];
        $settings = Setting::getAll();

        foreach ($settings as $row) {
            $inputs[$row->key] = 'required|max:500';
        }

        return $inputs;
    }
}
