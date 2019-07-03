<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models\Chat;

use App\Models\Vendor\BaseModel;
use App\Models\Vendor\Facades\ChatGroupMember;
use App\Models\Vendor\Facades\ChatMessage;

class ChatGroup extends BaseModel
{
    protected $tableName = 'chat_groups';
    protected $writableColumns = [
        'created_by_id', 'name',
        'is_archived', 'is_spam'
    ];

    protected $inputBooleans = ['is_archived', 'is_spam'];
    protected $columnHasRelations = ['created_by_id'];

    public function __construct(array $attributes = [])
    {
        $this->fillable($this->writableColumns);
        parent::__construct($attributes);
    }

    protected function customQuerySelectList(): array
    {
        // count how many members
        $count_participant = 'SELECT COUNT(*) FROM chat_group_members WHERE chat_groups.id = chat_group_members.chat_group_id';
        // query chat member
        $member = 'SELECT COUNT(*) FROM chat_group_members WHERE chat_groups.id = chat_group_members.chat_group_id AND chat_group_members.member_id = ';

        // member id
        $member_id = $this->params['is_member'] ?? 0;

        // message one person (private message 1 to 1)
        $pm = $this->params['private_message'] ?? ['to' => 0, 'from' => 0];

        // is two user is member of the group
        $pm_to = $member . ($pm['to'] ?? 0);
        $pm_from = $member . ($pm['from'] ?? 0);

        // self
        $me = __me() ? __me()->id : 0;

        // queries
        $query = [
            // did you sent a message for your self?
            'me_only' => 'IF((' . $count_participant . ') = 1, IF((' . $member . $me . ') = 1, 1, 0), 0)',

            // let's count member of this group
            'count_members' => '(' . $count_participant . ')',

            // are you a member of this group?
            'is_member' => '(SELECT chat_group_members.member_id FROM chat_group_members WHERE 
                chat_groups.id = chat_group_members.chat_group_id AND chat_group_members.member_id = ' . $member_id . ' LIMIT 1)',

            // do you have admin access?
            'is_admin' => '(SELECT chat_group_members.is_admin FROM chat_group_members WHERE 
                chat_groups.id = chat_group_members.chat_group_id AND chat_group_members.member_id = ' . $me . ' LIMIT 1)',

            // do you have any unread message?
            'has_unread' => 'IF((SELECT COUNT(*) FROM chat_group_members WHERE 
                chat_groups.id = chat_group_members.chat_group_id AND 
                chat_group_members.is_seen = 0 AND 
                chat_group_members.member_id = ' . $me . ') > 0, 1, 0)',

            // is some member mark this group archive?
            'has_archive' => 'IF((SELECT COUNT(*) FROM chat_group_members WHERE 
                chat_groups.id = chat_group_members.chat_group_id AND 
                chat_group_members.is_archive = 1 AND 
                chat_group_members.member_id = ' . $me . ') > 0, 1, 0)',

            // are message one person only?
            'has_private_message' => 'IF((' . $count_participant . ') = 2, 
                IF((' . $pm_to . ') > 0, 
                    IF((' . $pm_to . ') = 1, 
                        IF((' . $pm_from . ') > 0, 
                            IF((' . $pm_from . ') = 1, 1, 0), 
                        0), 
                    0), 
                0), 
            0)',

            // this group name has empty name
            'has_empty_name' => 'IF(IFNULL(chat_groups.name, 1) = 1, 1, 0)',
        ];

        // search email, username, first name, last name and full name
        $search = $this->hasParams('search');
        if ($search) {
            $query['search_frn_member'] = '(SELECT users.first_name FROM chat_group_members 
                JOIN users ON chat_group_members.member_id = users.id 
                WHERE chat_group_members.chat_group_id = chat_groups.id AND 
                (users.first_name LIKE "%' . $search . '%") LIMIT 1)';

            $query['search_ltn_member'] = '(SELECT users.last_name FROM chat_group_members 
                JOIN users ON chat_group_members.member_id = users.id 
                WHERE chat_group_members.chat_group_id = chat_groups.id AND 
                (users.last_name LIKE "%' . $search . '%") LIMIT 1)';

            $query['search_fln_member'] = '(SELECT CONCAT(users.first_name, " ", users.last_name) FROM chat_group_members 
                JOIN users ON chat_group_members.member_id = users.id 
                WHERE chat_group_members.chat_group_id = chat_groups.id AND 
                (CONCAT(users.first_name, " ", users.last_name) LIKE "%' . $search . '%") LIMIT 1)';

            $query['search_email_member'] = '(SELECT users.email FROM chat_group_members 
                JOIN users ON chat_group_members.member_id = users.id 
                WHERE chat_group_members.chat_group_id = chat_groups.id AND 
                (users.email LIKE "%' . $search . '%") LIMIT 1)';

            $query['search_username_member'] = '(SELECT users.username FROM chat_group_members 
                JOIN users ON chat_group_members.member_id = users.id 
                WHERE chat_group_members.chat_group_id = chat_groups.id AND 
                (users.username LIKE "%' . $search . '%") LIMIT 1)';
        }

        return $query;
    }

    protected function dataFormatting($row)
    {
        $this->addDateFormatting($row);

        $row->messages = ChatMessage::fetchAll(['chat_group_id' => $row->id, 'limit_query' => (int)__settings('pagination')->value]);

        $row->members = ChatGroupMember::fetchAll(['chat_group_id' => $row->id]);
        $row->count_members = count($row->members);

        $row->latest_message = ChatMessage::fetch(['single' => true, 'chat_group_id' => $row->id, 'order_by_column' => 'updated_at']);
        $row->latest_message_at = $row->latest_message ? humanDate($row->latest_message->updated_at) : humanDate($row->updated_at);
        $row->latest_message_summary = $row->latest_message ? str_limit($row->latest_message->message, 22) : NULL;
        $row->photo = $row->latest_message ? $row->latest_message->profile_picture : url('assets/img/placeholders/profile_picture_male.png');

        $row->group_name = $row->name;
        $this->_cleanGroupName($row);

        return $row;
    }

    /**
     * Group name to members name if default group name is not available
     *
     * @param $row
     */
    private function _cleanGroupName($row)
    {
        if ($row->group_name) {
            return;
        }

        $_names = [];
        foreach ($row->members as $member) {
            if ($member->member_id !== __me()->id || count($row->members) == 1) {
                $_names[] = $member->full_name;
            }
        }

        if (count($_names)) {
            $row->group_name = implode(", ", $_names);
            $row->group_name = str_limit($row->group_name, 32);
        }
    }
}
