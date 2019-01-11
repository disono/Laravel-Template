<?php
/**
 * @author          Archie, Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (webmons.com), 2016-2018
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models\Chat;

use App\Models\Vendor\BaseModel;

class ChatGroupMember extends BaseModel
{
    protected static $tableName = 'chat_group_members';
    protected static $writableColumns = [
        'chat_group_id',
        'member_id', 'added_by_id',
        'is_mute', 'is_active', 'is_seen'
    ];
    protected static $inputBooleans = ['is_mute', 'is_active', 'is_seen'];

    public function __construct(array $attributes = [])
    {
        $this->fillable(self::$writableColumns);
        parent::__construct($attributes);
    }

    /**
     * List of select
     *
     * @return array
     */
    protected static function rawQuerySelectList()
    {
        return [
            'group_chat_name' => 'chat_groups.name',
            'member_full_name' => 'CONCAT(member.first_name, " ", member.last_name)',
            'creator_full_name' => 'CONCAT(creator.first_name, " ", creator.last_name)',
        ];
    }

    /**
     * Custom filters
     *
     * @param $query
     * @return mixed
     */
    public static function rawFilters($query)
    {
        $query->join('chat_groups', 'chat_group_members.chat_group_id', '=', 'chat_groups.id');
        $query->join('users AS member', 'chat_group_members.member_id', '=', 'member.id');
        $query->join('users AS creator', 'chat_group_members.added_by_id', '=', 'creator.id');
        return $query;
    }
}
