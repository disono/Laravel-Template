<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Profile
     *
     * @param $username
     * @return \Illuminate\Contracts\View\Factory
     */
    public function show($username)
    {
        $type = ($this->request->get('type') == 'id') ? 'id' : 'username';

        $user = User::single($username, $type);

        if (!$user) {
            return abort(404);
        }

        return theme('user.profile', ['profile' => $user]);
    }
}
