<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Authenticator
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $me = __me();

        if ($me) {
            if (!$me->is_email_verified && __settings('emailVerification')->value === 'enabled' &&
                !$this->_allowedRoute([
                    'user.setting.general', 'user.setting.general.update',
                    'api.v1.user.sync', 'api.v1.user.setting.general.update', 'api.v1.user.setting.security.update'
                ])) {

                if (request()->ajax()) {
                    return failedJSONResponse('Email verification is required to use the app.', 498);
                }

                return theme('errors.user.user_email_verification', [], 498);
            }

            if (!$me->is_phone_verified && __settings('phoneVerification')->value === 'enabled' &&
                !$this->_allowedRoute([
                    'auth.verify.phone', 'user.setting.general', 'user.setting.general.update',
                    'api.v1.user.sync', 'api.v1.user.setting.general.update', 'api.v1.user.setting.security.update'
                ])) {

                if (request()->ajax()) {
                    return failedJSONResponse('Phone verification is required to use the app.', 498);
                }

                return theme('errors.user.user_phone_verification', [], 498);
            }

            if (!$me->is_account_enabled && __settings('accountEnabled')->value === 'enabled') {
                if (request()->ajax()) {
                    return failedJSONResponse('Your account is disabled or remove from the system.', 498);
                }

                return theme('errors.user.user_account_enabled', [], 498);
            }
        }

        return $next($request);
    }

    private function _allowedRoute($routes = []): bool
    {
        if (!in_array(request()->route()->getName(), $routes)) {
            return false;
        }

        return true;
    }
}
