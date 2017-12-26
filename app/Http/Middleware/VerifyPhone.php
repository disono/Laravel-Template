<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

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
                !in_array($request->getPathInfo(), [
                    '/email/resend/verification',
                    '/email/verify',

                    '/phone/resend/verification',
                    '/phone/verify',

                    '/user/settings',
                    '/user/security',

                    '/logout'
                ])
            ) {
                if ($request->ajax()) {
                    return failed_json_response('Your phone number is not verified, please check your phone for validation code to verify your phone number.', 400);
                }

                return new Response(view('auth.verify_phone'));
            }
        }

        return $next($request);
    }
}
