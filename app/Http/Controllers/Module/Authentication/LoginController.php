<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Module\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Module\Auth\LoginRequest;
use App\Models\Vendor\Facades\AuthenticationHistory;
use App\Models\Vendor\Facades\Token;
use App\Models\Vendor\Facades\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
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
        $this->previousRouteName = app('router')
            ->getRoutes()
            ->match(app('request')->create(URL::previous()))
            ->getName();
    }

    /**
     * Show the application's login form.
     *
     * @return Response
     */
    public function loginAction()
    {
        if (!in_array($this->previousRouteName, [
            'auth.login', 'auth.login.process', 'auth.logout', 'auth.facebook', 'auth.facebook.callback',
            'auth.register', 'auth.register.process',
            'auth.password.forgot', 'auth.password.process.forgot', 'auth.password.recover', 'auth.password.process.recover',
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
     * @return bool|RedirectResponse
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

            return $this->_authenticationError(
                $request,
                'Too many attempts to login, please try again later after ' . $seconds . ' seconds.'
            );
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
     * Log the user out of the application.
     *
     * @return Response
     */
    public function logoutAction()
    {
        // login history
        $this->_logAuthentication('logout');

        // destroy token log
        $this->_destroyTokenLog();

        return $this->logout($this->request);
    }

    /**
     * Authentication error
     *
     * @param $request
     * @param $error
     * @return RedirectResponse
     */
    private function _authenticationError($request, $error)
    {
        if ($request->ajax()) {
            return failedJSONResponse(['username' => $error], 422, false);
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
        return [
            $type => $request->get('username'),
            'password' => $request->get('password'),
            'is_email_verified' => 1,
            'is_account_enabled' => 1
        ];
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
        __initializeSettings();

        // login history
        $this->_logAuthentication();

        // create session log
        $this->_createTokenLog();

        if ($this->request->ajax()) {
            return successJSONResponse([
                'redirect' => $this->redirectPath()
            ]);
        }

        return $this->redirect($this->redirectPath());
    }

    /**
     * Log the authentication
     * @param string $type
     */
    private function _logAuthentication($type = 'login')
    {
        if (!__me()) {
            return;
        }

        try {
            $userAgent = userAgent();

            AuthenticationHistory::store([
                'user_id' => __me()->id,
                'ip' => ipAddress(),
                'platform' => $userAgent->platform . ', ' . $userAgent->browserName,
                'type' => $type
            ]);
        } catch (\Exception $e) {
            logErrors('LoginController._logAuthentication: ' . $e->getMessage());
        }
    }

    /**
     * Create token log
     */
    private function _createTokenLog()
    {
        User::crateToken(__me(), 'client', session()->getId(), config('session.lifetime'));
    }

    /**
     * Delete the current token log
     */
    private function _destroyTokenLog()
    {
        Token::remove([
            'user_id' => __me()->id,
            'token' => session()->getId(),
            'source' => 'client'
        ]);
    }
}
