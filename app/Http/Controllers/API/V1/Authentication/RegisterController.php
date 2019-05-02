<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\API\V1\Authentication;

use App\Http\Controllers\API\APIController;
use App\Http\Requests\API\V1\Authentication\RegisterRequest;
use App\Models\User;

class RegisterController extends APIController
{
    /**
     * Register user
     *
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerAction(RegisterRequest $request)
    {
        $user = User::register($request->all());
        if ($user) {
            return $this->json($user);
        }

        return $this->json('Failed to register, please try again later.', 422);
    }
}
