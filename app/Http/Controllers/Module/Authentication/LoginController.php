<?php
/**
 * @author          Archie, Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (webmons.com), 2016-2018
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Module\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Module\Auth\LoginRequest;
use App\Models\AuthenticationHistory;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\URL;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/dashboard';
    private $previousRouteKey = 'WBPreviousURL';
    private $previousRouteName = null;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest')->except('logoutAction');
        $this->previousRouteName = app('router')->getRoutes()->match(app('request')->create(URL::previous()))->getName();
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function loginAction()
    {
        if (!in_array($this->previousRouteName, [
            'auth.login', 'auth.login.process', 'auth.logout', 'auth.facebook', 'auth.facebook.callback',
            'auth.register', 'auth.register.process',
            'auth.password.forgot', 'auth.password.processForgot', 'auth.password.recover', 'auth.password.processRecover',
            'auth.verify.email', 'auth.verify.phone'
        ])) {
            request()->session()->put($this->previousRouteKey, URL::previous());
        }

        return $this->view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param LoginRequest $request
     * @return bool|\Illuminate\Http\RedirectResponse
     */
    public function processAction(LoginRequest $request)
    {
        // redirect to previous url
        $previous_url = session($this->previousRouteKey, $this->redirectTo);
        if ($previous_url && $previous_url != url('/')) {
            $this->redirectTo = $previous_url;
        }

        // too many attempt
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            $seconds = $this->limiter()->availableIn(
                $this->throttleKey($request)
            );

            return $this->_authenticationError($request, 'Too many attempts to login, please try again later after ' .
                $seconds . ' seconds.');
        }

        // authenticate
        if ($this->_authenticate($request)) {
            return $this->_authenticated($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);
        return $this->_authenticationError($request, 'Invalid username or password.');
    }

    /**
     * Authentication error
     *
     * @param $request
     * @param $error
     * @return \Illuminate\Http\RedirectResponse
     */
    private function _authenticationError($request, $error)
    {
        if ($request->ajax()) {
            return failedJSONResponse([
                'username' => $error
            ], 422, false);
        } else {
            return $this->redirect()->withErrors([
                'username' => $error,
            ]);
        }
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
        if (auth()->attempt($this->_requestInputs($request), $request->filled('remember'))) {
            return true;
        }

        // authenticate using username
        if (auth()->attempt($this->_requestInputs($request, 'username'), $request->filled('remember'))) {
            return true;
        }

        return false;
    }

    /**
     * Default request inputs for login
     *
     * @param $request
     * @param string $type
     * @return array
     */
    private function _requestInputs($request, $type = 'email')
    {
        return [$type => $request->get('username'), 'password' => $request->get('password'),
            'is_email_verified' => 1, 'is_account_enabled' => 1];
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
        initialize_settings();

        // login history
        $this->_logAuthentication();

        if ($this->request->ajax()) {
            return successJSONResponse([
                'redirect' => $this->redirectPath()
            ]);
        }

        return $this->redirect($this->redirectPath());
    }

    /**
     * Log the authentication
     */
    private function _logAuthentication()
    {
        if (__me()) {
            $userAgent = userAgent();
            AuthenticationHistory::store([
                'user_id' => __me()->id,
                'ip' => ipAddress(),
                'platform' => $userAgent->platform . ', ' . $userAgent->browserName,
                'type' => 'login'
            ]);
        }
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function logoutAction()
    {
        return $this->logout($this->request);
    }
}
