<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
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
        if (Auth::check()) {
            if (Auth::user()->email_confirmed != 1 &&
                Request::getPathInfo() != '/email/resend/verification' &&
                Request::getPathInfo() != '/email/verify' &&
                Request::getPathInfo() != '/logout'
            ) {
                return new Response(view('auth.verify'));
            }
        }

        return $next($request);
    }
}
