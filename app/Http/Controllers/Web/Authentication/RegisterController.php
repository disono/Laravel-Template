<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Web\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Auth\Register;
use App\Models\EmailVerification;
use App\Models\Slug;
use App\Models\User;
use App\Notifications\RegisterNotification;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class RegisterController extends Controller
{
    use RegistersUsers;

    // default redirect after registration
    public $redirectTo = '/dashboard';

    /**
     * Create a new authentication controller instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Show the application registration form.
     *
     * @return bool|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function registerAction()
    {
        $this->view = 'auth.';
        return $this->response('register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param Register $request
     * @return bool
     */
    public function processAction(Register $request)
    {
        $creation = $this->create($request->all());

        if ($creation) {
            Auth::loginUsingId($creation->id, true);

            if ($request->all()) {
                return success_json_response([
                    'redirect' => $this->redirectPath()
                ]);
            }
        } else {
            if ($request->all()) {
                return failed_json_response('Failed to register, please try again later.');
            }
        }

        return $this->redirectResponse($this->redirectPath());
    }

    /**
     * Verify email
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function verifyEmailAction(Request $request)
    {
        // email verifications
        $verification = EmailVerification::where('token', $request->get('token'))
            ->where('email', $request->get('email'))->first();

        // check if token is valid
        if (!$verification) {
            return view('errors.error')->withErrors([
                'message' => 'Token is not found.'
            ]);
        }

        // check if token is not expired
        if (strtotime($verification->expired_at) <= time()) {
            return view('errors.error')->withErrors([
                'message' => 'Token is expired please resend another verification token. To resend another token please login.'
            ]);
        }

        // get user
        $user = User::where('email', $verification->email)->first();

        // update user email is verified
        User::where('id', $user->id)->update([
            'email_confirmed' => 1
        ]);

        // clean all verification token
        EmailVerification::where('email', $user->email)->delete();

        // login user
        Auth::loginUsingId($user->id);
        return $this->response('auth.registration_successful');
    }

    /**
     * Resend email registration verification
     */
    public function resendVerificationAction()
    {
        $auth = Auth::user();
        Notification::send($auth, new RegisterNotification($auth));

        return $this->redirectResponse();
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        $create = User::create([
            'first_name' => ucfirst($data['first_name']),
            'last_name' => ucfirst($data['last_name']),
            'password' => bcrypt($data['password']),
            'email' => $data['email'],
            'role' => 'client',
            'enabled' => 1
        ]);

        if ($create) {
            Slug::store([
                'source_id' => $create->id,
                'source_type' => 'user',
                'name' => $data['username']
            ]);

            try {
                // send email for email verification
                Notification::send($create, new RegisterNotification($create));
            } catch (\Exception $e) {
                error_logger('Mail Notification Registration Failed: ' . $e->getMessage());
            }
        }

        return $create;
    }
}