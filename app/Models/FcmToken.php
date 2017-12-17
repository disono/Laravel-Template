<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models;

class FcmToken extends AppModel
{
    protected static $table_name = 'fcm_tokens';

    /**
     * Get users tokens
     *
     * @param $user_id
     *
     * @return null
     */
    public static function fetch($user_id)
    {
        $tokens = [];
        $data = self::where('user_id', $user_id)->get();

        foreach ($data as $row) {
            $tokens[] = $row->token;
        }

        return $tokens;
    }

    /**
     * Get all data no pagination
     *
     * @param array $params
     * @return null
     */
    public static function getAll($params = [])
    {
        $params['all'] = true;
        return self::fetch($params);
    }

    /**
     * Add token
     *
     * @param $user_id
     * @param $token
     */
    public static function add($user_id, $token)
    {
        if (!self::check($token)) {
            self::insert([
                'user_id' => $user_id,
                'token' => $token
            ]);
        } else {
            self::where('token', $token)->update([
                'user_id' => $user_id
            ]);
        }
    }

    /**
     * Check token if exists
     *
     * @param $token
     *
     * @return bool
     */
    public static function check($token)
    {
        if (self::where('token', $token)->count()) {
            return true;
        }

        return false;
    }

    /**
     * Delete token
     *
     * @param $token
     * @return bool
     */
    public static function remove($token)
    {
        return (bool)self::where('token', $token)->delete();
    }
}
