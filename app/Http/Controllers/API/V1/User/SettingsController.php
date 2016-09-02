<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */
namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\User;

class SettingsController extends Controller
{
    /**
     * Update settings
     *
     * @param Requests\API\V1\UserSettingsUpdate $request
     * @return array
     */
    public function postSettings(Requests\API\V1\UserSettingsUpdate $request)
    {
        $id = authenticated_id();

        // update user
        $inputs = $request->only([
            'first_name',
            'last_name',

            'address',
            'about',
            'phone',
            'gender',
            'birthday',

            'country_id'
        ]);
        $inputs['image'] = $request->file('image');
        User::edit($id, $inputs);

        return success_json_response(User::single($id));
    }

    /**
     * Update security
     *
     * @param Requests\API\V1\UserSecurityUpdate $request
     * @return array
     */
    public function postSecurity(Requests\API\V1\UserSecurityUpdate $request)
    {
        $id = authenticated_id();

        // update user
        $inputs = $request->only([
            'email',
            'password'
        ]);
        User::edit($id, $inputs);

        return success_json_response(User::single($id));
    }
}
