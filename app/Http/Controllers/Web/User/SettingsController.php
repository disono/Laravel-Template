<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\UserUpdateSecurity;
use App\Http\Requests\Web\UserUpdateSettings;
use App\Models\Country;
use App\Models\User;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->view = 'user.';
        parent::__construct();
    }

    /**
     * Get user settings
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex()
    {
        $this->title = 'General Account Settings';
        $this->content['user'] = me();
        $this->content['countries'] = Country::all();
        return $this->response('settings');
    }

    /**
     * Update settings
     *
     * @param UserUpdateSettings $request
     * @return bool
     */
    public function postUpdate(UserUpdateSettings $request)
    {
        // update user
        $inputs = $request->all();
        $inputs['image'] = $request->file('image');
        User::edit(me()->id, $inputs);

        return $this->redirectResponse();
    }

    /**
     * Get view for user security settings
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function security()
    {
        $this->title = 'Security';
        $this->content['user'] = User::single(me()->id);
        return $this->response('security');
    }

    /**
     * Update user security settings
     *
     * @param UserUpdateSecurity $request
     * @return bool
     */
    public function updateSecurity(UserUpdateSecurity $request)
    {
        // update user
        $inputs = $request->all();
        User::edit(me()->id, $inputs);
        return $this->redirectResponse();
    }
}
