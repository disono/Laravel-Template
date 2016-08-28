<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */
namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Get user information
     *
     * @param $id
     * @return array
     */
    public function getShow($id)
    {
        return success_json_response(User::single($id));
    }
}
