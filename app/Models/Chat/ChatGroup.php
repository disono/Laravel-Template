<?php
/**
 * @author          Archie, Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (webmons.com), 2016-2018
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models\Chat;

use App\Models\Vendor\BaseModel;

class ChatGroup extends BaseModel
{
    protected static $tableName = 'chat_groups';
    protected static $writableColumns = [
        'created_by_id', 'name',
        'is_deleted', 'is_archived', 'is_spam'
    ];
    protected static $inputBooleans = ['is_deleted', 'is_archived', 'is_spam'];

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
        // user id
        $member_id = self::$params['is_member'] ?? 0;
        $two_participant = self::$params['two_participants'] ?? [0, 0];

        $pat_one = 'SELECT COUNT(*) FROM chat_group_members WHERE chat_groups.id = chat_group_members.chat_group_id AND chat_group_members.member_id = ' . $two_participant[0] . ' LIMIT 1';
        $pat_two = 'SELECT COUNT(*) FROM chat_group_members WHERE chat_groups.id = chat_group_members.chat_group_id AND chat_group_members.member_id = ' . $two_participant[1] . ' LIMIT 1';

        return [
            'count_members' => 'SELECT COUNT(*) FROM chat_group_members WHERE chat_groups.id = chat_group_members.chat_group_id',
            'is_member' => 'SELECT member_id FROM chat_group_members ' .
                'WHERE chat_groups.id = chat_group_members.chat_group_id AND chat_group_members.member_id = ' . (int)$member_id . ' LIMIT 1',
            'has_two_participants' => 'IF(' . $pat_one . ' > 0, IF(' . $pat_two . ' > 0, 1, 0), 0) '
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
        $row->group_name = $row->name;
        $row->members = ChatGroupMember::fetchAll(['chat_group_id' => $row->id]);
        $row->messages = ChatMessage::fetch(['chat_group_id' => $row->id]);

        self::_cleanGroupName($row);

        return $row;
    }

    /**
     * Group name to members name if default group name is not available
     *
     * @param $row
     */
    private static function _cleanGroupName($row)
    {
        if ($row->group_name) {
            return;
        }

        $_names = [];
        foreach ($row->members as $member) {
            $_names[] = $member->member_full_name;
        }

        if (count($_names)) {
            $row->members = implode(", ", $_names);
            $row->members = str_limit($row->members, 32);
        }
    }
}
