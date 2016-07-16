<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Setting;

class SettingUpdate extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

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
