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
use App\Models\EmailVerification;
use App\Models\Slug;
use App\Models\User;
use App\Notifications\RegisterNotification;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Validator;

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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function process(Request $request)
    {
        return $this->register($request);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'username' => 'required|max:100|alpha_dash|unique:slugs,name|not_in:' . exclude_slug(),
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
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

            // send email for email verification
            Notification::send($create, new RegisterNotification($create));
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

        return redirect('dashboard');
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