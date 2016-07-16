<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: http://www.webmons.com
 * License: Apache 2.0
 */

namespace App\Http\Controllers\Web\User;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    private $user;

    public function __construct()
    {
        $this->user = me();
    }

    /**
     * Admin dashboard
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex()
    {
        $content['title'] = app_title('Dashboard');

        if ($this->user->role != 'client') {
            return admin_view('user.dashboard', $content);
        } else {
            return theme('user.dashboard', $content);
        }
    }
}
