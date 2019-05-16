<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\API\V1\Authentication;

use App\Http\Controllers\API\APIController;
use App\Http\Requests\API\V1\Authentication\RecoverPasswordRequest;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Notification;

class RecoverController extends APIController
{
    /**
     * Send a reset link
     *
     * @param RecoverPasswordRequest $request
     * @return JsonResponse
     */
    public function passwordAction(RecoverPasswordRequest $request)
    {
        $user = User::where('email', $request->get('email'))->first();
        Notification::send($user, new ResetPasswordNotification($user));
        unset($user->link);
        return $this->json($user);
    }
}
