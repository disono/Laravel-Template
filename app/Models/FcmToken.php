<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FcmToken extends Model
{
    /**
     * Get users tokens
     *
     * @param $user_id
     *
     * @return null
     */
    public static function get($user_id)
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
        return self::get($params);
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
     */
    public static function remove($token)
    {
        self::where('token', $token)->delete();
    }
}
