<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAjax
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->ajax()) {
            return abort(405);
        }

        return $next($request);
    }
}
