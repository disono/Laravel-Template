<?php

namespace App\Http\Middleware;

use App\Models\Vendor\Facades\Token;
use App\Models\Vendor\Facades\User;
use Closure;
use Illuminate\Http\Request;

class Authenticator
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $me = __me();

        if ($me) {
            if ($this->_isEmailVerified($me)) {
                return $this->_setView('Email verification is required to use the app.', 'errors.user.user_email_verification');
            }

            if ($this->_isPhoneVerified($me)) {
                return $this->_setView('Phone verification is required to use the app.', 'errors.user.user_phone_verification');
            }

            if ($this->_isAccountEnabled($me)) {
                return $this->_setView('Your account is disabled or remove from the system.', 'errors.user.user_account_enabled');
            }

            // update token expiration
            $this->_updateTokenLog($me);

            // log last user session
            User::setActiveAt();
        }

        return $next($request);
    }

    private function _isEmailVerified($me)
    {
        return !$me->is_email_verified &&
            __settings('emailVerification')->value === 'enabled' &&
            !$this->_allowedRoute([
                'module.user.setting.general', 'module.user.setting.general.update',
                'api.v1.user.sync', 'api.v1.user.setting.general.update', 'api.v1.user.setting.security.update'
            ]);
    }

    private function _allowedRoute($routes = []): bool
    {
        if (!in_array(request()->route()->getName(), $routes)) {
            return false;
        }

        return true;
    }

    private function _setView($msg, $view)
    {
        if (request()->ajax()) {
            return failedJSONResponse($msg, 498);
        }

        return theme($view, [], 498);
    }

    private function _isPhoneVerified($me)
    {
        return !$me->is_phone_verified &&
            __settings('phoneVerification')->value === 'enabled' &&
            !$this->_allowedRoute([
                'auth.verify.phone', 'module.user.setting.general', 'module.user.setting.general.update',
                'api.v1.user.sync', 'api.v1.user.setting.general.update', 'api.v1.user.setting.security.update'
            ]);
    }

    private function _isAccountEnabled($me)
    {
        return !$me->is_account_enabled && __settings('accountEnabled')->value === 'enabled';
    }

    private function _updateTokenLog($me)
    {
        Token::edit([
            'user_id' => $me->id,
            'token' => session()->getId(),
            'source' => 'client'
        ], [
            'expired_at' => expiredAt(config('session.lifetime'))
        ], TRUE, FALSE);
    }
}
