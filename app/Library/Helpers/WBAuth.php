<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */
namespace App\Library\Helpers;

use App\Models\AuthenticationToken;
use App\Models\Authorization;
use App\Models\AuthorizationRole;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class WBAuth
{
    /**
     * Get authenticated user
     *
     * @return null
     */
    public static function authUser()
    {
        if (auth()->check()) {
            return User::single(auth()->user()->id);
        } else if (api_auth()) {
            return User::single(authenticated_id());
        }

        return null;
    }

    /**
     * Authorization on routes
     *
     * @param array $roles
     * @param $user_id
     * @param bool $route_check
     * @return mixed
     */
    public static function authorizeMe($roles = [], $user_id, $route_check = true)
    {
        $user = User::find($user_id);
        if (!$user) {
            return false;
        }

        if (!in_array($user->role, $roles)) {
            return false;
        }

        $is_role_found = false;
        if ($route_check) {
            foreach ($roles as $role) {
                $user_role = Role::where('slug', $user->role)->first();
                $default_role = Role::where('slug', $role)->first();

                if ($user_role && $default_role) {
                    if ($user_role->identifier === $default_role->identifier) {
                        $select[] = DB::raw('authorizations.identifier, authorizations.name as authorization_name');
                        $query = AuthorizationRole::select($select)
                            ->join('authorizations', 'authorization_roles.authorization_id', '=', 'authorizations.id')
                            ->where('authorization_roles.role_id', $user_role->id)
                            ->where('authorizations.identifier', request()->route()->getName());

                        if ($query->count()) {
                            $is_role_found = true;
                        }
                    }
                }
            }
        } else {
            $is_role_found = true;
        }

        return $is_role_found;
    }

    /**
     * Authorize user to access routes
     *
     * @param $user_id
     * @param bool $abort
     * @return bool
     */
    public static function authorizeRoute($user_id = 0, $abort = true)
    {
        if (!$user_id) {
            if (auth()->check()) {
                $user_id = me()->id;
            } else if (authenticated_id()) {
                $user_id = authenticated_id();
            }
        }

        $user = User::find($user_id);
        $allow = false;

        if ($user) {
            // check if user role exists
            $user_role = Role::where('slug', $user->role)->first();
            if ($user_role) {
                $select[] = DB::raw('authorizations.identifier, authorizations.name as authorization_name');
                $query = AuthorizationRole::select($select)
                    ->join('authorizations', 'authorization_roles.authorization_id', '=', 'authorizations.id')
                    ->where('authorization_roles.role_id', $user_role->id)
                    ->where('authorizations.identifier', request()->route()->getName());

                if ($query->count()) {
                    $allow = true;
                }
            }
        }

        if ($abort && !$allow) {
            abort(403);
        }

        return $allow;
    }

    /**
     * Check if user exists
     *
     * @param string $input_name
     * @return bool
     */
    public static function APICheckAuth($input_name = 'authenticated_id')
    {
        $user_id = Input::get($input_name);
        $user = User::single($user_id);

        if ($user) {
            // check if account is enabled
            if (!$user->enabled) {
                return false;
            }

            return $user;
        }

        return false;
    }

    /**
     * Authenticate
     *
     * @param bool $response
     * @return bool|\Illuminate\Http\JsonResponse|null
     */
    public static function APIAuthenticateJWT($response = false)
    {
        $user = null;

        try {
            // initialize token key
            self::initializeTokenKey();

            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                error_logger('Token not found.');
                return ($response) ? false : failed_json_response('Token not found.');
            }
        } catch (TokenExpiredException $e) {
            error_logger('Token is expired, ' . $e->getMessage());
            return ($response) ? false : failed_json_response('Token is expired, ' . $e->getMessage());
        } catch (TokenInvalidException $e) {
            error_logger('Token is invalid, ' . $e->getMessage());
            return ($response) ? false : failed_json_response('Token is invalid, ' . $e->getMessage());
        } catch (JWTException $e) {
            error_logger('Token is absent, ' . $e->getMessage());
            return ($response) ? false : failed_json_response('Token is absent, ' . $e->getMessage());
        }

        if (!$user) {
            error_logger('User is not found.');
            return ($response) ? false : failed_json_response('User is not found.');
        }

        return $user;
    }

    /**
     * Initialize token key
     */
    public static function initializeTokenKey()
    {
        // token key
        $auth_token = AuthenticationToken::where('token_key', get_request_value('token_key'))->first();
        if (!$auth_token) {
            return;
        }

        // check if user id is correct
        if (authenticated_id() != $auth_token->user_id) {
            return;
        }

        // secret key
        config(['jwt.secret' => $auth_token->secret_key]);
    }

    /**
     * Is resource authorize
     *
     * @param $user
     * @return bool
     */
    public static function resourceAuthorize($user)
    {
        $role = Role::where('slug', $user->role)->first();
        if ($role) {
            $authorization_role = AuthorizationRole::where('role_id', $role->id)->first();
            if ($authorization_role) {
                $is_authorize = Authorization::where('identifier', request()->route()->getName())->first();
                if ($is_authorize) {
                    return true;
                }
            }
        }

        return false;
    }
}