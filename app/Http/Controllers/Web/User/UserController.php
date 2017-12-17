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

class UserController extends Controller
{
    public function __construct()
    {
        $this->response_type = 'json';
        parent::__construct();
    }

    /**
     * Search for users
     */
    public function index()
    {
        $options = [];
        if (me()->role == 'client' && false) {
            // add limit to users search
            // if client
            $options['role'] = '';
        }

        $this->content = User::fetch(request_options('search', $options));
        return $this->response();
    }
}
