<?php

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
        $me = me();
        $options = [];

        // add limit to users search
        // if client
        if ($me->role == 'client' && false) {
            $options['role'] = '';
        }

        $this->content = User::get(request_options([
            'search'
        ], $options));

        return $this->response();
    }
}
