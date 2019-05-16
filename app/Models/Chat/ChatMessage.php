<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models\Chat;

use App\Models\File;
use App\Models\User;
use App\Models\Vendor\BaseModel;

class ChatMessage extends BaseModel
{
    protected $tableName = 'chat_messages';
    protected $writableColumns = [
        'chat_group_id', 'user_id',
        'message',
    ];

    protected $files = ['file_msg'];

    public function __construct(array $attributes = [])
    {
        $this->fillable($this->writableColumns);
        parent::__construct($attributes);
    }

    /**
     * Custom filters
     *
     * @param $query
     * @return mixed
     */
    public function rawFilters($query)
    {
        $query->join('chat_groups', 'chat_messages.chat_group_id', '=', 'chat_groups.id');
        $query->join('users AS sender', 'chat_messages.user_id', '=', 'sender.id');
        return $query;
    }

    /**
     * List of select
     *
     * @return array
     */
    protected function rawQuerySelectList()
    {
        $_me = __me() ? __me()->id : 0;

        return [
            'group_name' => 'chat_groups.name',
            'sender_full_name' => 'CONCAT(sender.first_name, " ", sender.last_name)',
            'sender_first_name' => 'sender.first_name',
            'sender_last_name' => 'sender.last_name',
            'is_me' => 'IF(chat_messages.user_id = ' . $_me . ', 1, 0)'
        ];
    }

    /**
     * Add formatting to data
     *
     * @param $row
     * @return mixed
     */
    protected function dataFormatting($row)
    {
        // file types
        $row->files = [];
        foreach ((new File())->types() as $type) {
            $row->files[$type] = (new File())->fetchAll(['table_name' => $this->tableName, 'table_id' => $row->id, 'type' => $type]);
        }

        $row->profile_picture = (new User())->profilePicture($row->user_id);
        $row->formatted_created_at = humanDate($row->created_at);

        return $row;
    }
}
