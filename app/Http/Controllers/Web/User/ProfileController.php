<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Models\User;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->view = 'user.';
        parent::__construct();
    }

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

        $this->content['profile'] = $user;
        return $this->response('profile');
    }
}
