<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */
namespace App\Http\Controllers\Web\User;

use App\Models\Country;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\UserUpdateSecurity;
use App\Http\Requests\Web\UserUpdateSettings;
use App\Models\User;

class SettingsController extends Controller
{
    /**
     * Get user settings
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex()
    {
        $user = me();
        
        $content['user'] = User::single($user->id);

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
        $user = me();
        
        // update user
        $inputs = $request->all();
        $inputs['image'] = $request->file('image');
        User::edit($user->id, $inputs);

        return redirect()->back();
    }

    /**
     * Get view for user security settings
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function security()
    {
        $user = me();
        
        $content['user'] = User::single($user->id);
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
        $user = me();
        
        // update user
        $inputs = $request->all();
        User::edit($user->id, $inputs);

        return redirect()->back();
    }
}
