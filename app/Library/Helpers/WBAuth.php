<?php
/**
 * Author: Archie, Disono (disono.apd@gmail.com)
 * Website: www.webmons.com
 * License: Apache 2.0
 */
namespace App\Library\Helpers;

use Illuminate\Support\Facades\Auth;

class WBAuth
{
    /**
     * Get authenticated user
     *
     * @return null
     */
    public static function authUser()
    {
        if (Auth::check()) {
            return \App\User::single(Auth::user()->id);
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
        $user = \App\User::find($user_id);
        if (!$user) {
            return false;
        }

        if (!in_array($user->role, $roles)) {
            return false;
        }

        $is_role_found = false;
        if ($route_check) {
            foreach ($roles as $role) {
                $user_role = \App\Role::where('slug', $user->role)->first();
                $default_role = \App\Role::where('slug', $role)->first();

                if ($user_role && $default_role) {
                    if ($user_role->identifier === $default_role->identifier) {
                        $select[] = \Illuminate\Support\Facades\DB::raw('authorizations.identifier, authorizations.name as authorization_name');
                        $query = \App\AuthorizationRole::select($select)
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
     * Check if user exists
     *
     * @param string $column_name
     * @return bool
     */
    public static function APICheckAuth($column_name = 'user_id')
    {
        $user_id = \Illuminate\Support\Facades\Input::get($column_name);
        $user = \App\User::single($user_id);

        if ($user) {
            // check if account is enabled
            if (!$user->enabled) {
                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * Is resource authorize
     *
     * @param $user
     * @return bool
     */
    public static function resourceAuthorize($user)
    {
        $role = \App\Role::where('slug', $user->role)->first();
        if ($role) {
            $authorization_role = \App\AuthorizationRole::where('role_id', $role->id)->first();
            if ($authorization_role) {
                $is_authorize = \App\Authorization::where('identifier', request()->route()->getName())->first();
                if ($is_authorize) {
                    return true;
                }
            }
        }

        return false;
    }
}