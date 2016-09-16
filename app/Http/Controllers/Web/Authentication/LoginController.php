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
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Validator;

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

    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogin()
    {
        return view('auth.login');
    }

    /**
     * Log the user out of the application.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function getLogout(Request $request)
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

    /**
     * Handle a login request to the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        $data = $this->login($request);

        try {
            // login history
            if (auth()->check()) {
                AuthHistory::store([
                    'user_id' => auth()->user()->id,
                    'ip' => get_ip_address(),
                    'platform' => get_user_agent(),
                    'type' => 'login'
                ]);
            }
        } catch (\Exception $e) {
            error_logger('AuthHistory: ' . $e->getMessage());
        }

        return $data;
    }
}