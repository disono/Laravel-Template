<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
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
        $this->title = 'Settings';
        $this->content['settings'] = Setting::getAll();
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

        return $this->redirectResponse('admin/settings');
    }
}
