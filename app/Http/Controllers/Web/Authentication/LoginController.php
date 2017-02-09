<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */
namespace App\Http\Controllers\Web\Authentication;

use App\Http\Controllers\Controller;
use App\Models\AuthHistory;
use App\Models\ECommerce\Cart;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new authentication controller instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function loginView()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function process(Request $request)
    {
        $cart_contents = Cart::content();

        // redirect to previous url
        if(!str_contains($request->session()->previousUrl(), "/login")) {
            $this->redirectTo = $request->session()->previousUrl();
        }

        // required
        if (!$request->get('email') || !$request->get('password')) {
            $error = 'Both username and password is required.';

            return $this->_authentication_error($request, $error);
        }

        // too many attempt
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            $error = 'Too many attempts to login.';

            return $this->_authentication_error($request, $error);
        }

        // authenticate
        if (auth()->attempt(['email' => $request->get('email'), 'password' => $request->get('password'),
            'email_confirmed' => 1, 'enabled' => 1])
        ) {
            return $this->_authenticated($cart_contents, $request);
        } else {
            $error = 'Invalid username or password.';

            // If the login attempt was unsuccessful we will increment the number of attempts
            // to login and redirect the user back to the login form. Of course, when this
            // user surpasses their maximum number of attempts they will get locked out.
            $this->incrementLoginAttempts($request);

            return $this->_authentication_error($request, $error);
        }
    }

    /**
     * Authenticated user
     *
     * @param $cart_contents
     * @param $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    private function _authenticated($cart_contents, $request)
    {
        $request->session()->regenerate();
        $this->clearLoginAttempts($request);

        try {
            // login history
            if (me()) {
                AuthHistory::store([
                    'user_id' => me()->id,
                    'ip' => get_ip_address(),
                    'platform' => get_user_agent(),
                    'type' => 'login'
                ]);

                // migrate cart
                Cart::migrate($cart_contents);
            }
        } catch (\Exception $e) {
            error_logger('AuthHistory: ' . $e->getMessage());
        }

        if ($this->request->ajax()) {
            return success_json_response(null, null, null, [
                'redirect' => $this->redirectPath()
            ]);
        }

        return redirect($this->redirectPath());
    }

    /**
     * Authentication error
     *
     * @param $request
     * @param $error
     * @return \Illuminate\Http\RedirectResponse
     */
    private function _authentication_error($request, $error)
    {
        if ($request->ajax()) {
            return failed_json_response([
                $this->username() => $error
            ], 422, false);
        } else {
            return redirect()->back()->withErrors([
                $this->username() => $error,
            ]);
        }
    }

    /**
     * Log the user out of the application.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function logoutProcess(Request $request)
    {
        try {
            // logout history
            AuthHistory::store([
                'user_id' => auth()->user()->id,
                'ip' => get_ip_address(),
                'platform' => get_user_agent(),
                'type' => 'logout'
            ]);
        } catch (\Exception $e) {
            error_logger('AuthHistory: ' . $e->getMessage());
        }

        return $this->logout($request);
    }
}