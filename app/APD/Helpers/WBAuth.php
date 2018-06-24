<?php
/**
 * @author          Archie, Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (webmons.com), 2016-2018
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

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
        } else if (authAPI()) {
            return User::single(authID());
        }

        return null;
    }
}

if (!function_exists('authID')) {
    /**
     * Get the authenticated id
     *
     * @return array|\Illuminate\Http\Request|string
     */
    function authID()
    {
        $id = (__me()) ? __me()->id : 0;

        return (int)(request()->header('authenticated_id')) ?
            request()->header('authenticated_id') :
            request('authenticated_id', $id);
    }
}

if (!function_exists('authAPI')) {
    /**
     * Check the authenticated API
     *
     * @return null
     */
    function authAPI()
    {
        $user = User::find(authID());
        if ($user) {
            // check if account is enabled
            if (!$user->is_account_enabled || $user->is_account_enabled == 0) {
                return null;
            }

            return $user;
        }

        return null;
    }
}

if (!function_exists('jwt')) {
    /**
     * Check API JWT
     *
     * @param bool $response
     * @return bool|\Illuminate\Http\JsonResponse|null
     */
    function jwt($response = false)
    {
        $user = null;

        try {
            jwt_initialize_token_by_key();

            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return ($response) ? false : failedJSONResponse('Token not found.');
            }
        } catch (TokenExpiredException $e) {
            return ($response) ? false : failedJSONResponse('Token is expired, ' . $e->getMessage());
        } catch (TokenInvalidException $e) {
            return ($response) ? false : failedJSONResponse('Token is invalid, ' . $e->getMessage());
        } catch (JWTException $e) {
            return ($response) ? false : failedJSONResponse('Token is absent, ' . $e->getMessage());
        }

        if (!$user) {
            return ($response) ? false : failedJSONResponse('User is not valid.');
        }

        return $user;
    }
}

if (!function_exists('jwt_initialize_token_by_key')) {
    /**
     * Initialize JWT token by key
     */
    function jwt_initialize_token_by_key()
    {
        if (!Schema::hasTable('tokens')) {
            return;
        }

        // token
        $token = \App\Models\Token::where('key', request()->header('token_key'))->first();
        if (!$token) {
            return;
        }

        // check if user id is correct
        if (authID() != $token->user_id) {
            return;
        }

        // secret key
        config(['jwt.secret' => $token->secret]);
    }
}

if (!function_exists('isAuthorize')) {
    /**
     * Is user authorize
     *
     * @param array $roles
     * @param bool $boolean_only
     * @param string $delimiter
     * @return bool|\Illuminate\Http\JsonResponse
     */
    function isAuthorize($roles = [], $boolean_only = false, $delimiter = '|')
    {
        $auth_id = (auth()->check()) ? auth()->user()->id : 0;
        $roles = (is_array($roles)) ? $roles : explode($delimiter, $roles);
        $response = authorizeMe($roles, $auth_id, false);

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
     * @param array $roles
     * @param $user_id
     * @param bool $route_check
     * @return bool
     */
    function authorizeMe($roles = [], $user_id, $route_check = true)
    {
        $user_role = \App\Models\User::find($user_id)->role;

        if (!$user_role) {
            return false;
        }

        if (!in_array($user_role->slug, $roles)) {
            return false;
        }

        if ($route_check) {
            $authentication_role = \App\Models\AuthorizationRole::where('route', request()->route()->getName())
                ->where('role_id', $user_role->id)
                ->first();

            if (!$authentication_role) {
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
            if (auth()->check()) {
                $user_id = __me()->id;
            } else if (authID()) {
                $user_id = authID();
            }
        }

        if (!$user_id) {
            return false;
        }

        $user_role = User::find($user_id)->role;
        if (!$user_role) {
            return false;
        }

        $authentication_role = \App\Models\AuthorizationRole::where('route', request()->route()->getName())
            ->where('role_id', $user_role->id)
            ->first();
        if (!$authentication_role) {
            return false;
        }

        return true;
    }
}

