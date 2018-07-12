<?php
/**
 * @author          Archie, Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (webmons.com), 2016-2018
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models;

use App\Models\Vendor\BaseModel;

class FirebaseNotification extends BaseModel
{
    protected static $tableName = 'firebase_notifications';
    protected static $writableColumns = [
        'user_id', 'title', 'content', 'type', 'topic_name', 'token'
    ];

    public function __construct(array $attributes = [])
    {
        $this->fillable(self::$writableColumns);
        parent::__construct($attributes);
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function sendTo($token, $title, $body)
    {
        try {
            FCMSend($token, $title, $body);
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function topic($topic_name, $title, $body)
    {
        try {
            FCMTopic($topic_name, $title, $body);
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * After successful save
     *
     * @param $query
     * @param $inputs
     * @throws \Exception
     */
    public static function actionStoreAfter($query, $inputs)
    {
        try {
            if ($query->type === 'topic' && $query->topic_name) {
                FCMTopic($query->topic_name, $query->title, $query->content);
            } else if ($query->type === 'token' && $query->token) {
                FCMSend($query->token, $query->title, $query->content);
            }
        } catch (\Exception $e) {
            throwError($e->getMessage());
        }
    }

    /**
     * After updating the data
     *
     * @param $query
     * @param $inputs
     * @throws \Exception
     */
    public static function actionEditAfter($query, $inputs)
    {
        try {
            if ($query->type === 'topic' && $query->topic_name) {
                FCMTopic($query->topic_name, $query->title, $query->content);
            } else if ($query->type === 'token' && $query->token) {
                FCMSend($query->token, $query->title, $query->content);
            }
        } catch (\Exception $e) {
            throwError($e->getMessage());
        }
    }
}
