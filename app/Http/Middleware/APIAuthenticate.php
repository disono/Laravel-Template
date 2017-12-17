<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Tymon\JWTAuth\Middleware\BaseMiddleware;

class APIAuthenticate extends BaseMiddleware
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
        $jwt = api_auth_jwt();

        if ($jwt && !($jwt instanceof User)) {
            return $jwt;
        }

        $this->events->fire('tymon.jwt.valid', $jwt);

        return $next($request);
    }
}