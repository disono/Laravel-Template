<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthenticationToken extends Model
{
    /**
     * Create token for authentication
     *
     * @param $user
     * @return null
     */
    public static function createToken($user)
    {
        $auth = User::find($user->id);

        if ($auth) {
            $secret_key = str_random(32);
            $token_key = str_random(32) . time() . str_random(16);

            $id = self::insertGetId([
                'user_id' => $auth->id,
                'secret_key' => $secret_key,
                'token_key' => $token_key
            ]);

            if ($id) {
                $user->secret_key = $secret_key;
                $user->token_key = $token_key;

                // add sql timestamp
                $user->server_timestamp = sql_date();

                return $user;
            }
        }

        return null;
    }
}
