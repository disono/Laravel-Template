<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\API\V1\Authenticate;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\PasswordRecovery;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
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

    use SendsPasswordResetEmails;

    /**
     * Reset the given user's password.
     *
     * @param PasswordRecovery $request
     * @return \Illuminate\Http\Response
     */
    public function postRecovery(PasswordRecovery $request)
    {
        config(['mail.from' => [
            'address' => env('MAIL_FROM_ADDRESS', ''),
            'name' => 'Password Reset',
        ]]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        if ($response === Password::RESET_LINK_SENT) {
            return success_json_response(trans($response));
        }

        // If an error was returned by the password broker, we will get this message
        // translated so we can notify a user of the problem. We'll redirect back
        // to where the users came from so they can attempt this process again.
        return failed_json_response(['email' => trans($response)]);
    }
}
