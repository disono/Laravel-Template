<?php
/**
 * @author          Archie, Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (webmons.com), 2016-2018
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Module\Chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Module\Chat\CreateGroupChat;
use App\Http\Requests\Module\Chat\SendMessage;
use App\Models\Chat\ChatGroup;
use App\Models\Chat\ChatGroupMember;
use App\Models\Chat\ChatMessage;

class MessageController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->theme = 'chat';
    }

    public function showAction($user_id = 0)
    {
        // search for group with two members only if user_id is available
        // if group is not available create
        $group = ($user_id) ? ChatGroup::fetch(['count_members' => 2, 'two_participants' => [$user_id, __me()->id], 'has_two_participants' => 1, 'single' => true]) : null;

        if (!$group) {
            // create a group chat
            $group = ChatGroup::store(['created_by_id' => __me()->id]);

            // add to group chat
            if ($group) {
                ChatGroupMember::store(['group_id' => $group->id, 'user_id' => __me()->id, 'added_by_id' => __me()->id]);
                ChatGroupMember::store(['group_id' => $group->id, 'user_id' => $user_id, 'added_by_id' => __me()->id]);
                $group = ChatGroup::single($group->id);
            }
        }

        return $this->view('show', ['group' => $group]);
    }

    public function showGroupAction($group_id)
    {
        // show messages on group with validation if current user is existing as member of the group
        return $this->_isMember($group_id, ChatGroup::single($group_id));
    }

    public function sendAction(SendMessage $request)
    {
        // send message to group
        // check if send to list of users or group id
        $inputs = $request->all();
        $inputs['file_msg'] = $request->file('file_msg');
        return $this->_isMember($request->get('group_id'), ChatMessage::store($inputs));
    }

    public function storeGroupAction(CreateGroupChat $request)
    {
        // create and store a new group with members including the creator
        $inputs = $request->all();
        $inputs['created_by_id'] = __me()->id;
        return $this->json(ChatGroup::store($inputs));
    }

    public function leaveGroupAction($group_id, $user_id)
    {
        // leave a group
        return $this->_isMember($group_id, ChatGroupMember::remove(null, ['group_id' => $group_id, 'user_id' => $user_id]));
    }

    public function addToGroupAction($group_id, $user_id)
    {
        // add a new user to group
        return $this->_isMember($group_id, ChatGroupMember::store(['group_id' => $group_id, 'user_id' => $user_id, 'added_by_id' => __me()->id]));
    }

    private function _isMember($group_id, $data)
    {
        if ($this->_checkIsMember($group_id) <= 0) {
            return $this->error(404, __me()->full_name . ' is not a member of this group chat.');
        }

        return $this->json($data);
    }

    private function _checkIsMember($group_id)
    {
        return ChatGroupMember::where('chat_group_id', $group_id)->where('member_id', __me()->id)->count();
    }
}
