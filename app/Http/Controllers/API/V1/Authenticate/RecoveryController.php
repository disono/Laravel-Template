<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */
namespace App\Http\Controllers\API\V1\Authenticate;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\PasswordRecovery;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class RecoveryController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Reset the given user's password.
     *
     * @param PasswordRecovery $request
     * @return \Illuminate\Http\Response
     */
    public function postReset(PasswordRecovery $request)
    {
        return $this->sendResetLinkEmail($request);
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validateSendResetLinkEmail($request);

        $broker = $this->getBroker();

        try {
            $response = Password::broker($broker)->sendResetLink(
                $this->getSendResetLinkEmailCredentials($request),
                $this->resetEmailBuilder()
            );

            switch ($response) {
                case Password::RESET_LINK_SENT:
                    return success_json_response($response);
                case Password::INVALID_USER:
                    return failed_json_response([
                        'email' => 'Invalid user email.'
                    ]);
                default:
                    return failed_json_response($response);
            }
        } catch (\Exception $e) {
            return failed_json_response($e->getMessage());
        }
    }
}
