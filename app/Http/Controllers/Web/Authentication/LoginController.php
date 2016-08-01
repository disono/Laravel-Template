<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: http://www.webmons.com
 * License: Apache 2.0
 */

namespace App\Http\Controllers\Web\Authentication;

use Illuminate\Http\Request;
use App\AuthHistory;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class LoginController extends Controller
{
    use AuthenticatesAndRegistersUsers;

    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use ThrottlesLogins;

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
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogout()
    {
        try {
            // logout history
            AuthHistory::insert([
                'user_id' => auth()->user()->id,
                'ip' => get_ip_address(),
                'platform' => get_user_agent(),
                'type' => 'logout',
                'created_at' => sql_date()
            ]);
        } catch (\Exception $e) {
            error_logger('AuthHistory: ' . $e->getMessage());
        }

        return $this->logout();
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
                AuthHistory::insert([
                    'user_id' => auth()->user()->id,
                    'ip' => get_ip_address(),
                    'platform' => get_user_agent(),
                    'type' => 'login',
                    'created_at' => sql_date()
                ]);
            }
        } catch (\Exception $e) {
            error_logger('AuthHistory: ' . $e->getMessage());
        }

        return $data;
    }
}