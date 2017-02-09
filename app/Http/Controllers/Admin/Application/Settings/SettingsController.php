<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */
namespace App\Http\Controllers\Admin\Application\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Setting;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->view = 'settings.';
        $this->view_type = 'admin';
        parent::__construct();
    }

    /**
     * List of data
     *
     * @return mixed
     */
    public function index()
    {
        $this->content['settings'] = Setting::getAll();
        $this->title = 'Settings';

        return $this->response('show');
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
