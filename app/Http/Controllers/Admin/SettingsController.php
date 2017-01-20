<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * List of data
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $content['title'] = app_title('Settings');
        $content['settings'] = Setting::getAll();
        $content['request'] = $request;

        return admin_view('settings.show', $content);
    }

    /**
     * Update data
     *
     * @param Requests\Admin\SettingUpdate $request
     * @return mixed
     */
    public function update(Requests\Admin\SettingUpdate $request)
    {
        foreach ($request->all() as $key => $value) {
            Setting::edit(null, [
                'value' => $value
            ], [
                'key' => $key
            ]);
        }

        return redirect('admin/settings');
    }
}
