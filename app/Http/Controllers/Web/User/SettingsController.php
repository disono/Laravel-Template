<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: http://www.webmons.com
 * License: Apache 2.0
 */

namespace App\Http\Controllers\Web\User;

use App\Country;
use App\Http\Requests\Web\UserUpdateSecurity;
use App\Http\Requests\Web\UserUpdateSettings;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    private $user;

    public function __construct()
    {
        $this->user = me();
    }

    /**
     * Get user settings
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex()
    {
        $content['user'] = User::single($this->user->id);

        $content['countries'] = Country::all();
        $content['title'] = app_title('General Account Settings');

        return theme('user.settings', $content);
    }

    /**
     * Update settings
     *
     * @param UserUpdateSettings $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postUpdate(UserUpdateSettings $request)
    {
        // update user
        $inputs = $request->all();
        $inputs['image'] = $request->file('image');
        User::edit($this->user->id, $inputs);

        return redirect()->back();
    }

    /**
     * Get view for user security settings
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function security()
    {
        $content['user'] = User::single($this->user->id);
        $content['title'] = app_title('Security');

        return theme('user.security', $content);
    }

    /**
     * Update user security settings
     *
     * @param UserUpdateSecurity $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSecurity(UserUpdateSecurity $request)
    {
        // update user
        $inputs = $request->all();
        User::edit($this->user->id, $inputs);
        
        return redirect()->back();
    }
}
