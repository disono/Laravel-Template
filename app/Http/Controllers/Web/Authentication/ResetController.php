<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Web\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ResetController extends Controller
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
     * Create a new password controller instance.
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest');
        $this->view = 'auth.passwords.';
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function getRecoverAction()
    {
        return $this->response('email');
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postRecoverAction(Request $request)
    {
        config(['mail.from' => [
            'address' => env('MAIL_FROM_ADDRESS', ''),
            'name' => 'Password Reset',
        ]]);

        return $this->sendResetLinkEmail($request);
    }
}
