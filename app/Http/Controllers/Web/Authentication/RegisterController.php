<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */

namespace App\Http\Controllers\Web\Authentication;

/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
 */

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
        $this->middleware('guest', ['except' => [
            'resendVerification', 'verifyEmail'
        ]]);
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function registerView()
    {
        return $this->showRegistrationForm();
    }

    /**
     * Handle a registration request for the application.
     *
     * @param Register $request
     * @return \Illuminate\Http\Response
     */
    public function process(Register $request)
    {
        $creation = $this->create($request->all());

        if ($creation) {
            Auth::loginUsingId($creation->id, true);

            if ($request->all()) {
                return success_json_response(null, null, null, [
                    'redirect' => $this->redirectPath()
                ]);
            }
        } else {
            if ($request->all()) {
                return failed_json_response('Failed to register, please try again later.');
            }
        }

        return redirect($this->redirectPath());
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
            } catch (\Swift_SwiftException $e) {
                error_logger('Mail Notification Registration Failed: ' . $e->getMessage());
            }
        }

        return $create;
    }

    /**
     * Verify email
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function verifyEmail(Request $request)
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

        return view('auth.registration_successful');
    }

    /**
     * Resend email registration verification
     */
    public function resendVerification()
    {
        $auth = Auth::user();
        Notification::send($auth, new RegisterNotification($auth));

        return redirect()->back();
    }
}