<?php

namespace App\Http\Middleware;

use App\Models\Vendor\Facades\Token;
use App\Models\Vendor\Facades\User;
use Closure;
use Illuminate\Http\Request;

class APIAuthenticate
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
        $jwt = jwtDecode();
        if ($jwt !== true) {
            return $jwt;
        }

        // update token expiration
        $this->_updateTokenLog();

        // log last user session
        User::setActiveAt();

        return $next($request);
    }

    private function _updateTokenLog()
    {
        Token::edit([
            'user_id' => __me()->id,
            'key' => request()->header('tkey'),
            'source' => 'mobile'
        ], [
            'expired_at' => expiredAt(21600)
        ], TRUE, FALSE);
    }
}
