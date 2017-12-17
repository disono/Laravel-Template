<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->view = 'user.';
        parent::__construct();
    }

    /**
     * Admin dashboard
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex()
    {
        $user = me();
        $this->title = 'Dashboard';
        if ($user->role != 'client') {
            $this->view_type = 'admin';
            return $this->response('dashboard');
        } else {
            $this->view_type = 'user';
            return $this->response('dashboard');
        }
    }
}
