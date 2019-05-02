<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\API\APIController;
use App\Models\User;

class UserController extends APIController
{
    /**
     * Sync user profile
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function syncAction()
    {
        return $this->json(User::single(__me()->id));
    }

    /**
     * Fetch user profile
     *
     * @param $username
     * @return \Illuminate\Http\JsonResponse
     */
    public function profileAction($username)
    {
        $user = User::single($username, 'username');
        if (!$user) {
            return $this->json(exceptionMessages('USER_NOT_FOUND'), 404);
        }

        return $this->json($user);
    }
}
