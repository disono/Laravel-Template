<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

use App\Models\AuthorizationRole;
use App\Models\Vendor\Facades\Token;
use App\Models\Vendor\Facades\User;
use Firebase\JWT\JWT;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

if (!function_exists('me')) {
    /**
     * Get current authenticated user
     *
     * @param int $id
     * @return null
     */
    function me($id = 0)
    {
        if ($id) {
            return User::single($id);
        }

        if (auth()->check()) {
            return User::single(auth()->user()->id);
        } else if (isAccountEnabled()) {
            return User::single(authId());
        }

        return null;
    }
}

if (!function_exists('authId')) {
    /**
     * Get the authenticated id
     *
     * @return array|Request|string
     */
    function authId()
    {
        $_r = request();
        return $_r->header('uid') ? $_r->header('uid') : request('uid', __me() ? __me()->id : 0);
    }
}

if (!function_exists('isAccountEnabled')) {
    /**
     * Check if account is still valid
     *
     * @return null
     */
    function isAccountEnabled()
    {
        $user = User::find(authId());
        if (!$user) {
            return NULL;
        }

        // check if account is enabled
        if (!$user->is_account_enabled) {
            return null;
        }

        return $user;
    }
}

if (!function_exists('jwtEncode')) {
    /**
     * Encode jwt
     *
     * @param string $exp
     * @return string|null
     */
    function jwtEncode($exp = '+ 2 hours')
    {
        if (!__me()) {
            return NULL;
        }

        $sub = 'uid';
        $iss = __me()->id;
        $iat = time();
        $tokenLog = Token::fetchAll(['single' => true, 'user_id' => __me()->id, 'token' => session()->getId(), 'source' => 'client']);

        if (!$tokenLog) {
            return NULL;
        }

        $token = array(
            "iss" => $iss,
            "sub" => $sub,
            "iat" => $iat,
            "exp" => strtotime($exp, time()),
            "nbf" => strtotime('- 1 minutes', time()),
            "jti" => md5("jti." . $iss . "." . $sub . "." . $iat . '.' . $tokenLog->token)
        );

        return JWT::encode($token, $tokenLog->secret, 'HS256');
    }
}

if (!function_exists('jwt')) {
    /**
     * Decode jwt
     *
     * iss: The issuer of the token = user_id
     * sub: This holds the identifier for the token (defaults to user id) = 'uid'
     * iat: When the token was issued (unix timestamp)
     * exp: The token expiry date (unix timestamp)
     * nbf: The earliest point in time that the token can be used (unix timestamp)
     * jti: A unique identifier for the token (md5 of the sub and iat claims) = MD5("jti." + iss + "." + sub + "." + iat)
     *
     * @return bool|JsonResponse|null
     */
    function jwtDecode()
    {
        // authentication using jwt is not required
        // good only for development purposes
        if (__settings('jwtAuthentication')->value !== 'enabled') {
            return true;
        }

        $jwt = request()->bearerToken();

        // token is present in header
        if (!$jwt) {
            return failedJSONResponse('Token not found.');
        }

        // search token
        $tokenLog = getTokenLog();
        if (!$tokenLog) {
            return failedJSONResponse('Token not found base on your "tkey".');
        }

        // decode jwt
        try {
            setTimezone();

            /*
             * When checking nbf, iat or expiration times,
             * we want to provide some extra leeway time to
             * account for clock skew.
             */
            JWT::$leeway = 10;

            // Decodes a JWT string into a PHP object.
            $decoded = JWT::decode($jwt, $tokenLog->secret, ['HS256']);
        } catch (Exception $exception) {
            return failedJSONResponse('Token failed to validate for the following reason ' . $exception->getMessage() . '.');
        }

        // if token is expired
        if (time() > $decoded->exp) {
            return failedJSONResponse('Token is expired.');
        }

        // if token is too early to validate
        if (time() < $decoded->nbf) {
            return failedJSONResponse('Token is too early to validate.');
        }

        // token valid
        if (md5("jti." . $decoded->iss . "." . $decoded->sub . "." . $decoded->iat . '.' . $tokenLog->token) != $decoded->jti) {
            return failedJSONResponse('Token jti is invalid.');
        }

        // lets check the user id
        if (authId() != $decoded->iss || $decoded->sub != 'uid') {
            return failedJSONResponse('Token has invalid user id.');
        }

        return true;
    }
}

if (!function_exists('getTokenLog')) {
    /**
     * Initialize JWT token by key
     */
    function getTokenLog()
    {
        if (!Schema::hasTable('tokens') && !authId()) {
            return NULL;
        }

        // token
        $token = Token::where('key', request()->header('tkey'))->where('user_id', authId())->first();
        if (!$token) {
            return NULL;
        }

        // secret key
        return $token;
    }
}

if (!function_exists('isAuthorize')) {
    /**
     * Is user authorize
     *
     * @param array $roles
     * @param bool $boolean_only
     * @param string $delimiter
     * @return bool|JsonResponse
     */
    function isAuthorize($roles = [], $boolean_only = false, $delimiter = '|')
    {
        $auth_id = auth()->check() ? auth()->user()->id : 0;
        $roles = is_array($roles) ? $roles : explode($delimiter, $roles);
        $response = authorizeMe($auth_id, $roles, false);

        if (!$response) {
            if ($boolean_only) {
                return false;
            }

            if (request()->ajax()) {
                return failedJSONResponse('You are not authorize to view this resource.');
            }

            abort(403);
        }

        return true;
    }
}

if (!function_exists('authorizeMe')) {
    /**
     * Authorize user by route
     *
     * @param $user_id
     * @param array $roles
     * @param bool $route_check
     *
     * @return bool
     */
    function authorizeMe($user_id, $roles = [], $route_check = true)
    {
        $user_role = User::find($user_id)->role;

        if (!$user_role) {
            return false;
        }

        if (!in_array($user_role->slug, $roles)) {
            return false;
        }

        if ($route_check) {
            if (!AuthorizationRole::where('route', request()->route()->getName())
                ->where('role_id', $user_role->id)
                ->first()) {
                return false;
            }
        }

        return true;
    }
}

if (!function_exists('authorizeRoute')) {
    /**
     * Check authorize route
     *
     * @param int $user_id
     * @return bool
     */
    function authorizeRoute($user_id = 0)
    {
        if (!$user_id) {
            if (__me()) {
                $user_id = __me()->id;
            } else if (authId()) {
                $user_id = authId();
            }
        }

        if (!$user_id) {
            return false;
        }

        $user_role = User::find($user_id)->role;
        if (!$user_role) {
            return false;
        }

        if (!AuthorizationRole::where('route', request()->route()->getName())
            ->where('role_id', $user_role->id)
            ->first()) {
            return false;
        }

        return true;
    }
}

if (!function_exists('destroySomeoneSession')) {
    /**
     * Delete someones session via session id
     *
     * @param null $id
     * @return bool
     */
    function destroySomeoneSession($id = NULL)
    {
        if (!$id) {
            return false;
        }

        $sessionFile = '../storage/framework/sessions/' . $id;
        if (!file_exists($sessionFile)) {
            return false;
        }

        if (!file_put_contents($sessionFile, 'n/a')) {
            return false;
        }

        if (!unlink($sessionFile)) {
            return false;
        }

        return true;
    }
}

