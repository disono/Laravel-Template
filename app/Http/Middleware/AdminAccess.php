<?php

namespace App\Http\Middleware;

use Closure;

class AdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param null $roles
     * @return mixed
     */
    public function handle($request, Closure $next, $roles = null)
    {
        if (!$roles) {
            $has_access = authorizeRoute();
            if (!$has_access) {
                if (request()->ajax()) {
                    return failedJSONResponse('Unauthorized access.', 498);
                }

                abort(403);
            }
        } else {
            $authorize = isAuthorize($roles);
            if ($authorize !== true) {
                return $authorize;
            }
        }

        return $next($request);
    }
}
