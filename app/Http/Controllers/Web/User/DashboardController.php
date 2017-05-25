<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Admin dashboard
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex()
    {
        $content['title'] = app_title('Dashboard');
        $user = me();

        if ($user->role != 'client') {
            return admin_view('user.dashboard', $content);
        } else {
            return theme('user.dashboard', $content);
        }
    }
}
