<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Middleware;

use App\Http\Controllers\Controller;
use Closure;
use Illuminate\Http\Response;

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
            if (
                auth()->user()->email_confirmed != 1 &&

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
                error_logger('1');

                if ($request->ajax()) {
                    return failed_json_response('Your email is not verified, please check your email for validation URL to verify your email.', 400);
                }

                $_controller = new Controller();
                $_controller->_js();
                $_controller->_seo();
                return new Response(theme('auth.verify_email', $_controller->content));
            }
        }

        return $next($request);
    }
}
