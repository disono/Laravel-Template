<?php
/**
 * @author          Archie, Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (webmons.com), 2016-2018
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models\Chat;

use App\Models\File;
use App\Models\Vendor\BaseModel;

class ChatMessage extends BaseModel
{
    protected static $tableName = 'chat_messages';
    protected static $writableColumns = [
        'chat_group_id', 'user_id',
        'message',
    ];

    protected static $files = ['file_msg'];

    public function __construct(array $attributes = [])
    {
        $this->fillable(self::$writableColumns);
        parent::__construct($attributes);
    }

    /**
     * Custom filters
     *
     * @param $query
     * @return mixed
     */
    public static function rawFilters($query)
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
    protected static function rawQuerySelectList()
    {
        $_me = __me() ? __me()->id : 0;

        return [
            'group_name' => 'chat_groups.name',
            'sender_full_name' => 'CONCAT(sender.first_name, " ", sender.last_name)',
            'is_me' => 'IF(chat_messages.user_id = ' . $_me . ', 1, 0)'
        ];
    }

    /**
     * Add formatting to data
     *
     * @param $row
     * @return mixed
     */
    protected static function dataFormatting($row)
    {
        $row->files = File::fetchAll(['table_name' => self::$tableName, 'table_id' => $row->id]);
        return $row;
    }
}
