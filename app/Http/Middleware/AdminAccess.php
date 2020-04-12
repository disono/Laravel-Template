<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param null $roles
     * @return mixed
     */
    public function handle($request, Closure $next, $roles = NULL)
    {
        if (!$roles) {
            $has_access = authorizeRoute();
            if (!$has_access) {
                if (request()->ajax()) {
                    return failedJSONResponse(exceptionMessages('AUTH_DENIED_ACCESS'), 498);
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
