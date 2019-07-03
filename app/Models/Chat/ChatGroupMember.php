<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models\Chat;

use App\Models\Vendor\BaseModel;
use App\Models\Vendor\Facades\User;

class ChatGroupMember extends BaseModel
{
    protected $tableName = 'chat_group_members';
    protected $writableColumns = [
        'chat_group_id',
        'member_id', 'added_by_id',
        'is_admin', 'is_mute', 'is_active', 'is_seen', 'is_archive'
    ];

    protected $inputBooleans = ['is_admin', 'is_mute', 'is_active', 'is_seen', 'is_archive'];
    protected $columnHasRelations = ['chat_group_id', 'member_id', 'added_by_id',];

    public function __construct(array $attributes = [])
    {
        $this->fillable($this->writableColumns);
        parent::__construct($attributes);
    }

    protected function customQueries($query): void
    {
        $query->join('chat_groups', 'chat_group_members.chat_group_id', '=', 'chat_groups.id');
        $query->join('users AS member', 'chat_group_members.member_id', '=', 'member.id');
        $query->join('users AS creator', 'chat_group_members.added_by_id', '=', 'creator.id');
    }

    protected function customQuerySelectList(): array
    {
        $me = (__me()) ? __me()->id : 0;

        return [
            'group_chat_name' => 'chat_groups.name',
            'full_name' => 'CONCAT(member.first_name, " ", member.last_name)',
            'member_gender' => 'member.gender',
            'creator_full_name' => 'CONCAT(creator.first_name, " ", creator.last_name)',
            'is_me' => 'IF(chat_group_members.member_id = ' . $me . ', 1, 0)',
        ];
    }

    protected function dataFormatting($row)
    {
        $this->addDateFormatting($row);
        $row->profile_picture = User::profilePicture($row->member_id, $row->member_gender);

        return $row;
    }
}
