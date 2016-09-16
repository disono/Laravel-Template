<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request;

class VerifyPhone
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            if (auth()->user()->phone_confirmed != 1 &&
                Request::getPathInfo() != '/email/resend/verification' &&
                Request::getPathInfo() != '/email/verify' &&

                Request::getPathInfo() != '/phone/resend/verification' &&
                Request::getPathInfo() != '/phone/verify' &&

                Request::getPathInfo() != '/user/settings' &&
                Request::getPathInfo() != '/user/security' &&

                Request::getPathInfo() != '/logout'
            ) {
                return new Response(view('auth.verify_phone'));
            }
        }

        return $next($request);
    }
}
