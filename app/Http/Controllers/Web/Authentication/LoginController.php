<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Web\Authentication;

use App\Http\Controllers\Controller;
use App\Models\AuthHistory;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
    private $previous_url_key = 'wb_previous_url';

    /**
     * Create a new authentication controller instance.
     */
    public function __construct()
    {
        parent::__construct();
        $this->view = 'auth.';
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Contracts\View\Factory
     */
    public function loginAction()
    {
        $previous_url = request()->session()->previousUrl();
        if (!str_contains($previous_url, "/login") &&
            !str_contains($previous_url, "/register") &&
            !str_contains($previous_url, "/password/recover")) {
            request()->session()->put($this->previous_url_key, $previous_url);
        }

        return $this->response('login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processAction(Request $request)
    {
        // redirect to previous url
        $previous_url = session($this->previous_url_key, $this->redirectTo);
        if ($previous_url && $previous_url != url('/')) {
            $this->redirectTo = $previous_url;
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
        if ($this->_authenticate($request)) {
            return $this->_authenticated($request);
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
     * Log the user out of the application.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function logoutAction(Request $request)
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
     * Authenticate using username or email
     *
     * @param $request
     * @return bool
     */
    private function _authenticate($request)
    {
        // authenticate using email
        if (auth()->attempt(['email' => $request->get('email'), 'password' => $request->get('password'),
            'email_confirmed' => 1, 'enabled' => 1])) {
            return true;
        }

        // authenticate using username
        $_user = User::where(DB::raw($this->_username()), $request->get('email'))
            ->where('email_confirmed', 1)
            ->where('enabled', 1)
            ->first();
        if ($_user && Hash::check($request->get('password'), $_user->password)) {
            auth()->loginUsingId($_user->id);
            return true;
        }

        return false;
    }

    /**
     * Username query string
     *
     * @return string
     */
    private function _username()
    {
        return '(SELECT name FROM slugs WHERE source_id = users.id AND source_type = "user")';
    }

    /**
     * Authenticated user
     *
     * @param $request
     * @return bool
     */
    private function _authenticated($request)
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
            }
        } catch (\Exception $e) {
            error_logger('AuthHistory: ' . $e->getMessage());
        }

        if ($this->request->ajax()) {
            return success_json_response([
                'redirect' => $this->redirectPath()
            ]);
        }

        return $this->redirectResponse($this->redirectPath());
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
            return $this->redirectResponse()->withErrors([
                $this->username() => $error,
            ]);
        }
    }
}