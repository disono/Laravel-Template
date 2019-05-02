<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Module\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Module\Auth\ForgotPassword;
use App\Http\Requests\Module\Auth\RecoverPassword;
use App\Models\AuthenticationHistory;
use App\Models\PasswordReset;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class ResetController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest')->except(['recoverAction', 'processRecoveryAction']);
    }

    /**
     * Forgot view form
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function forgotAction()
    {
        return $this->view('auth.forgot');
    }

    /**
     * Email the forgot link password
     *
     * @param ForgotPassword $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function processForgotAction(ForgotPassword $request)
    {
        $user = User::where('email', $request->get('email'))->first();
        Notification::send($user, new ResetPasswordNotification($user));

        return successJSONResponse([
            'redirect' => '/login'
        ]);
    }

    /**
     * Password recover form
     *
     * @param $token
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function recoverAction($token)
    {
        // check the toke if valid
        $check = $this->_checkToken($token);
        if ($check === false) {
            abort(400);
        }

        return $this->view('auth.recover', ['reset_token' => $check->token]);
    }

    /**
     * Check token if valid
     *
     * @param $token
     * @param null $email
     * @return mixed
     */
    private function _checkToken($token, $email = null)
    {
        $reset = PasswordReset::where('token', $token);
        if ($email) {
            $reset->where('email', $email);
        }

        $reset = $reset->first();

        // is token exists
        if (!$reset) {
            return false;
        }

        // if more than 1 hr token is expired
        if (countHours($reset->created_at, sqlDate()) >= 1) {
            return false;
        }

        return $reset;
    }

    /**
     * Change password process
     *
     * @param RecoverPassword $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function processRecoveryAction(RecoverPassword $request)
    {
        // check the toke if valid
        $check = $this->_checkToken($request->get('reset_token'), $request->get('email'));
        if ($check === false) {
            return failedJSONResponse([
                'email' => 'Invalid token or expired, please send a password reset again.'
            ]);
        }

        // user
        $user = User::where('email', $request->get('email'))->first();
        if (!$user) {
            return failedJSONResponse([
                'email' => 'Invalid email address.'
            ]);
        }

        // update user password
        User::where('email', $request->get('email'))->update(['password' => bcrypt($request->get('password'))]);

        // delete all password reset tokens
        PasswordReset::where('email', $request->get('email'))->delete();

        // login the user
        Auth::loginUsingId($user->id);
        initialize_settings();
        $this->_logAuthentication();

        return successJSONResponse([
            'redirect' => '/dashboard'
        ]);
    }

    /**
     * Log the authentication
     */
    private function _logAuthentication()
    {
        // authentication history
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
}
