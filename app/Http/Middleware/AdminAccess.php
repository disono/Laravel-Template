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
     * @param string $roles
     *  delimited with |
     *  role_name|role_name
     * @return mixed
     */
    public function handle($request, Closure $next, $roles = null)
    {
        if (!$roles) {
            $has_access = authorize_route();
            if (!$has_access) {
                return failed_json_response('Unauthorized access.', 498);
            }
        } else {
            $authorize = is_authorize($roles);
            if ($authorize !== true) {
                return $authorize;
            }
        }

        return $next($request);
    }
}
