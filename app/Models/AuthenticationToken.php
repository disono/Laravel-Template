<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models;

class AuthenticationToken extends AppModel
{
    protected static $table_name = 'authentication_tokens';

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
