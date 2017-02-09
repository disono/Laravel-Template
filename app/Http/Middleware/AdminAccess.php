<?php

namespace App\Http\Middleware;

use Closure;

class AdminAccess
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
        $has_access = authorize_route();
        if (!$has_access) {
            return failed_json_response('Unauthorized access.', 498);
        }

        return $next($request);
    }
}
