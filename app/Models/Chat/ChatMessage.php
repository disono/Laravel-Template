<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models\Chat;

use App\Models\Vendor\BaseModel;
use App\Models\Vendor\Facades\File;
use App\Models\Vendor\Facades\User;

class ChatMessage extends BaseModel
{
    protected $tableName = 'chat_messages';
    protected $writableColumns = [
        'chat_group_id', 'user_id',
        'message',
    ];

    protected $files = ['file_msg'];
    protected $columnHasRelations = ['chat_group_id', 'user_id'];

    public function __construct(array $attributes = [])
    {
        $this->fillable($this->writableColumns);
        parent::__construct($attributes);
    }

    protected function customQueries($query): void
    {
        $query->join('chat_groups', 'chat_messages.chat_group_id', '=', 'chat_groups.id');
        $query->join('users AS sender', 'chat_messages.user_id', '=', 'sender.id');
    }

    protected function customQuerySelectList(): array
    {
        $_me = __me() ? __me()->id : 0;

        return [
            'group_name' => 'chat_groups.name',
            'sender_full_name' => 'CONCAT(sender.first_name, " ", sender.last_name)',
            'sender_first_name' => 'sender.first_name',
            'sender_last_name' => 'sender.last_name',
            'sender_gender' => 'sender.gender',
            'is_me' => 'IF(chat_messages.user_id = ' . $_me . ', 1, 0)'
        ];
    }

    protected function dataFormatting($row)
    {
        $this->addDateFormatting($row);

        // file types
        $row->files = [];
        foreach (File::types() as $type) {
            $row->files[$type] = File::fetchAll(['table_name' => $this->tableName, 'table_id' => $row->id, 'type' => $type]);
        }

        // profile picture
        $row->profile_picture = User::profilePicture($row->user_id, $row->sender_gender);
        return $row;
    }
}
