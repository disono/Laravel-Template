<?php

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
        $jwt = jwt();
        if ($jwt && !($jwt instanceof User)) {
            return $jwt;
        }

        $this->events->fire('tymon.jwt.valid', $jwt);
        return $next($request);
    }
}
