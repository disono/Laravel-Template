<?php
/**
 * @author          Archie, Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (webmons.com), 2016-2018
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Module\Authentication;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Verification;
use App\Notifications\RegisterNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class VerifyController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Verify email address
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function emailAction()
    {
        return $this->_checkVerification('email');
    }

    /**
     * Verification form for phone
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function phoneAction()
    {
        return $this->view('auth.verification.phone');
    }

    /**
     * Verify phone
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function processPhoneAction()
    {
        return $this->_checkVerification('phone');
    }

    /**
     * Resend verification code
     *
     * @param $type
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function resendVerificationViewAction($type)
    {
        return $this->view('auth.verification.resend', ['type' => $type]);
    }

    /**
     * Resend verification code
     *
     * @param $type
     * @return bool
     */
    public function resendVerificationStoreAction($type)
    {
        $value = $this->request->get('type_value');

        // do we have a value to search for
        if (!$value) {
            return $this->redirect()->with('error', $type . ' is required.');
        }

        // did the phone or email exists
        $user = User::where($type, $value)->first();
        if (!$user) {
            return $this->redirect()->with('error', $type . ' is not registered.');
        }

        // did the old verification exists
        $verification = Verification::where('value', $value)->where('type', $type)->first();
        $user->renew_code = true;
        if ($verification) {
            // is expired
            if (strtotime($verification->expired_at) > time()) {
                $user->renew_code = false;
                $user->verification_code = $verification->token;
                $this->_resendCode($type, $user);
            } else {
                $this->_resendCode($type, $user);
            }
        } else {
            $this->_resendCode($type, $user);
        }

        if ($type == 'email') {
            return $this->redirect()->with('message', 'We already sent you a verification link for ' . $value . '. Thank You.');
        }

        return $this->redirect('verify/' . $type);
    }

    /**
     * Resend the verification code
     *
     * @param $type
     * @param $user
     */
    private function _resendCode($type, $user)
    {
        if ($type == 'phone') {
            $this->_resendPhone($user);
        } else if ($type == 'email') {
            $this->_resendEmail($user);
        }
    }

    /**
     * Resend verification to phone
     *
     * @param $user
     */
    private function _resendPhone($user)
    {
        // clean all verification before saving new
        Verification::where('value', $user->phone)->where('type', 'phone')->delete();

        // create token
        Verification::create([
            'user_id' => $user->id,
            'token' => ucwords(str_random(6)),
            'value' => $user->phone,
            'type' => 'phone',
            'expired_at' => expiredAt(1440)
        ]);
    }

    /**
     * Resend verification to email email
     *
     * @param $user
     */
    private function _resendEmail($user)
    {
        // send email for email verification
        Notification::send($user, new RegisterNotification($user));
    }

    /**
     * Check the verification
     *
     * @param $type
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    private function _checkVerification($type)
    {
        // verifications
        $verification = Verification::where('token', $this->request->get('token'))
            ->where('value', $this->request->get($type))
            ->where('type', $type)
            ->first();

        // check if token is valid
        if (!$verification) {
            return $this->view('errors.default', [
                'message' => 'Token or code is not found.'
            ]);
        }

        // check if token is not expired
        if (strtotime($verification->expired_at) <= time()) {
            return $this->view('errors.default', [
                'message' => 'Token is expired please resend another verification token. To resend another token please login.'
            ]);
        }

        // get user
        $user = User::where($type, $verification->value)->first();
        if (!$user) {
            return $this->view('errors.default', [
                'message' => 'Invalid ' . $type . '.'
            ]);
        }

        // update user is verified
        if ($type === 'email') {
            User::where('id', $user->id)->update([
                'is_email_verified' => 1
            ]);
        } else if ($type === 'phone') {
            User::where('id', $user->id)->update([
                'is_phone_verified' => 1
            ]);
        }

        // clean all verification token
        Verification::where('value', $user->$type)->where('type', $type)->delete();

        // login user
        Auth::loginUsingId($user->id);
        initialize_settings();

        return $this->view('auth.verification.success.' . $type);
    }
}
