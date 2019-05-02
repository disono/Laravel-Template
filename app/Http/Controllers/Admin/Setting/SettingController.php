<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Application\SaveSetting;
use App\Http\Requests\Admin\Application\SettingStore;
use App\Http\Requests\Admin\Application\SettingUpdate;
use App\Models\Setting;

class SettingController extends Controller
{
    protected $viewType = 'admin';

    public function __construct()
    {
        parent::__construct();
        $this->theme = 'settings';
    }

    public function showAction()
    {
        $this->setHeader('title', 'Application Settings');
        return $this->view('setting.show');
    }

    public function saveSettings(SaveSetting $request)
    {
        foreach (Setting::get() as $setting) {
            if ($request->has($setting->key)) {
                $value = ($setting->input_type == 'checkbox') ?
                    implode(',', $request->get($setting->key)) : $request->get($setting->key);

                Setting::where('key', $setting->key)->update([
                    'value' => $value
                ]);
            }
        }

        if ($request->ajax()) {
            return $this->json('Settings Save Successfully.');
        }

        return $this->redirect();
    }

    public function indexAction()
    {
        $this->setHeader('title', 'Application Settings');
        return $this->view('setting.application.index', ['settings' => Setting::fetch(requestValues('search'))]);
    }

    public function createAction()
    {
        $this->setHeader('title', 'Add Application Settings');
        return $this->view('setting.application.create');
    }

    public function storeAction(SettingStore $request)
    {
        $setting = Setting::store($request->all());
        if (!$setting) {
            return $this->json(['name' => 'Failed to crate a new setting.'], 422, false);
        }

        return $this->json(['redirect' => '/admin/setting/application/edit/' . $setting->id]);
    }

    public function editAction($id)
    {
        $this->setHeader('title', 'Edit Application Settings');
        $this->content['setting'] = Setting::single($id);
        if (!$this->content['setting']) {
            abort(404);
        }

        return $this->view('setting.application.edit');
    }

    public function updateAction(SettingUpdate $request)
    {
        Setting::edit($request->get('id'), $request->all());
        return $this->json('Setting is successfully updated.');
    }

    public function destroyAction($id)
    {
        Setting::remove($id);
        return $this->json('Setting is successfully deleted.');
    }
}
