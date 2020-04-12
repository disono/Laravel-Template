<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models;

use App\Models\Vendor\BaseModel;
use Exception;

class FirebaseNotification extends BaseModel
{
    protected $tableName = 'firebase_notifications';
    protected $writableColumns = [
        'user_id', 'title', 'content', 'type', 'topic_name', 'token'
    ];

    protected $columnHasRelations = ['user_id'];

    public function __construct(array $attributes = [])
    {
        $this->fillable($this->writableColumns);
        parent::__construct($attributes);
    }

    public function actionStoreAfter($query, $inputs)
    {
        $this->sendFCM($query);
    }

    /**
     * Send Notification
     *
     * @param $query
     * @throws Exception
     */
    private function sendFCM($query)
    {
        try {
            if ($query->type === 'topic' && $query->topic_name) {
                FCMTopic($query->topic_name, $query->title, $query->content);
            } else if ($query->type === 'token' && $query->token) {
                FCMSend($query->token, $query->title, $query->content);
            }
        } catch (Exception $e) {
            throwError($e->getMessage());
        }
    }

    public function actionEditAfter($query, $inputs)
    {
        $this->sendFCM($query);
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Send to
     *
     * @param $token
     * @param $title
     * @param $body
     * @return bool|string
     */
    public function sendTo($token, $title, $body)
    {
        try {
            FCMSend($token, $title, $body);
            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Send topic
     *
     * @param $topic_name
     * @param $title
     * @param $body
     * @return bool|string
     */
    public function topic($topic_name, $title, $body)
    {
        try {
            FCMTopic($topic_name, $title, $body);
            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
