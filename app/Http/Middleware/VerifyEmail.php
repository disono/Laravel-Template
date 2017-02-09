<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request;

class VerifyEmail
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
            if (auth()->user()->email_confirmed != 1 &&
                Request::getPathInfo() != '/email/resend/verification' &&
                Request::getPathInfo() != '/email/verify' &&

                Request::getPathInfo() != '/phone/resend/verification' &&
                Request::getPathInfo() != '/phone/verify' &&

                Request::getPathInfo() != '/user/settings' &&
                Request::getPathInfo() != '/user/security' &&

                Request::getPathInfo() != '/logout'
            ) {
                if ($request->ajax()) {
                    return failed_json_response('Your email is not verified, please check your email for validation URL to verify your email.', 400);
                }

                return new Response(view('auth.verify_email'));
            }
        }

        return $next($request);
    }
}
