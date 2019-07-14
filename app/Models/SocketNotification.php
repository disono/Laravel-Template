<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models;

use App\Models\Vendor\BaseModel;

class SocketNotification extends BaseModel
{
    protected $tableName = 'socket_notifications';
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
        $this->sendNotification($query);
    }

    public function actionEditAfter($query, $inputs)
    {
        $this->sendNotification($query);
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    private function sendNotification($query)
    {
        try {
            if ($query->type === 'topic' && $query->topic_name) {
                socketIOPublish($query->topic_name, ['title' => $query->title, 'content' => $query->content]);
            } else if ($query->type === 'token' && $query->token) {
                socketIOPublish($query->token, ['title' => $query->title, 'content' => $query->content]);
            }
        } catch (\Exception $e) {
            logErrors($e->getMessage());
            throwError($e->getMessage());
        }
    }
}
