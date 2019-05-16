<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

use App\Models\AuthorizationRole;
use App\Models\Token;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
            return (new User())->single($id);
        }

        if (auth()->check()) {
            return (new User())->single(auth()->user()->id);
        } else if (authAPI()) {
            return (new User())->single(authId());
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
        $user = User::find(authId());
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
     * @return bool|JsonResponse|null
     */
    function jwt($response = false)
    {
        $user = null;

        try {
            JWTInitializeTokenByKey();
            setTimezone();

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

if (!function_exists('JWTInitializeTokenByKey')) {
    /**
     * Initialize JWT token by key
     */
    function JWTInitializeTokenByKey()
    {
        if (!Schema::hasTable('tokens')) {
            return;
        }

        // token
        $token = Token::where('key', request()->header('token_key'))->first();
        if (!$token) {
            return;
        }

        // check if user id is correct
        if (authId() != $token->user_id) {
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
     * @return bool|JsonResponse
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
        $user_role = User::find($user_id)->role;

        if (!$user_role) {
            return false;
        }

        if (!in_array($user_role->slug, $roles)) {
            return false;
        }

        if ($route_check) {
            $authentication_role = AuthorizationRole::where('route', request()->route()->getName())
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

        $authentication_role = AuthorizationRole::where('route', request()->route()->getName())
            ->where('role_id', $user_role->id)
            ->first();
        if (!$authentication_role) {
            return false;
        }

        return true;
    }
}

if (!function_exists('setTimezone')) {
    /**
     * Set Timezone
     */
    function setTimezone()
    {
        try {
            if (!fetchRequestValue('device_timezone')) {
                return;
            }

            $collection = collect(timezone_identifiers_list());
            $searchFor = fetchRequestValue('device_timezone');
            $c = $collection->search(function ($item, $key) use ($searchFor) {
                return strpos($item, $searchFor) !== false;
            });
            $timezone = $collection->get($c);
            if (!$timezone) {
                return;
            }

            // secret key
            config(['timezone' => $timezone]);
        } catch (Exception $e) {
            logErrors('Error (App\APDApp\Helpers\WBAuth - setTimezone()): ' . $e->getMessage());
        }
    }
}

