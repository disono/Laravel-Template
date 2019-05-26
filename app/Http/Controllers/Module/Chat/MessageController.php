<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Module\Chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Module\Chat\CreateGroupChat;
use App\Http\Requests\Module\Chat\SendMessage;
use App\Http\Requests\Module\Chat\UpdateGroupChat;
use App\Models\Chat\ChatGroup;
use App\Models\Chat\ChatGroupMember;
use App\Models\Chat\ChatMessage;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    private $chatGroup;
    private $chatGroupMember;
    private $chatMessage;

    public function __construct()
    {
        parent::__construct();
        $this->theme = 'chat';

        $this->chatGroup = new ChatGroup();
        $this->chatGroupMember = new ChatGroupMember();
        $this->chatMessage = new ChatMessage();

        if (__settings('chat')->value === 'disabled') {
            abort(403);
        }

        $this->middleware(function ($request, $next) {
            $this->setAppView('app_scripts', [
                'assets/js/app/chat.js'
            ]);

            return $next($request);
        });
    }

    public function showAction()
    {
        return $this->view('show');
    }

    public function inboxAction($id, $type = 'user')
    {
        // search for group with two members only if member_id is available
        // if group is not available create
        if ($type === 'user') {
            if ($this->me->id == $id) {
                // I'm messaging my self
                $group = $this->chatGroup->fetch(['count_members' => 1, 'me_only' => $id, 'single' => true, 'is_deleted' => 0]);
            } else {
                // messaging another person
                $group = $this->chatGroup->fetch([
                    'count_members' => 2,
                    'private_message' => ['to' => $id, 'from' => $this->me->id], 'has_private_message' => 1, 'has_empty_name' => 1,
                    'single' => true, 'is_deleted' => 0
                ]);
            }

            if (!$group) {
                // create a group chat
                $group = $this->chatGroup->store(['created_by_id' => $this->me->id]);

                // add to group chat
                if ($group) {
                    $this->chatGroupMember->store([
                        'chat_group_id' => $group->id, 'member_id' => $this->me->id,
                        'added_by_id' => $this->me->id, 'is_admin' => 1
                    ]);

                    // add another member if not messaging your self
                    if ($this->me->id != $id) {
                        $this->chatGroupMember->store([
                            'chat_group_id' => $group->id, 'member_id' => $id,
                            'added_by_id' => $this->me->id
                        ]);
                    }

                    $group = $this->chatGroup->fetch(['single' => true, 'id' => $group->id, 'is_deleted' => 0]);
                }
            }
        } else {
            if (!$this->_checkIsMember($id)) {
                return $this->error(404);
            }

            $group = $this->chatGroup->fetch(['single' => true, 'id' => $id, 'is_deleted' => 0]);
        }

        if (!$group || !in_array($type, ['user', 'group'])) {
            return $this->error(404);
        }

        // mark as seen
        $this->chatGroupMember->where('chat_group_id', $group->id)->where('member_id', $this->me->id)->update(['is_seen' => 1]);

        return $this->view('show', ['group' => $group]);
    }

    private function _checkIsMember($group_id, $member_id = null)
    {
        $member_id = $member_id ? $member_id : $this->me->id;
        return $this->chatGroupMember->where('chat_group_id', $group_id)->where('member_id', $member_id)->count();
    }

    public function showGroupAction($group_id)
    {
        // show messages on group with validation if current user is existing as member of the group
        return $this->_isMember($group_id, $this->chatGroup->single($group_id));
    }

    private function _isMember($group_id, $data)
    {
        if ($this->_checkIsMember($group_id) <= 0) {
            return $this->json($this->me->full_name . ' is not a member of this group chat.', 404);
        }

        if ($data === null) {
            return $this->error(404);
        }

        return $this->json($data);
    }

    public function groupsAction()
    {
        $group = $this->chatGroup->fetch(requestValues(
            'search|has_unread|has_archive', [
                'is_member' => $this->me->id, 'is_deleted' => 0, 'order_by_column' => 'latest_message_at'
            ]
        ));

        if (!$group) {
            return $this->error(404);
        }

        return $this->json($group);
    }

    public function archiveAction($group_id, $status)
    {
        if (!in_array($status, [0, 1])) {
            return $this->json('Invalid status for archive.', 422);
        }

        return $this->_isMember($group_id, $this->chatGroupMember->where('chat_group_id', $group_id)
            ->where('member_id', $this->me->id)
            ->update(['is_archive' => $status]));
    }

    public function messagesAction($group_id)
    {
        return $this->_isMember($group_id, $this->chatMessage->fetch(requestValues('search', ['chat_group_id' => $group_id])));
    }

    public function sendAction(SendMessage $request)
    {
        // send message to group
        $inputs = $request->all();
        $inputs['file_msg'] = $request->file('file_msg');
        $inputs['user_id'] = __me()->id;
        return $this->_isMember($request->get('chat_group_id'), $this->chatMessage->store($inputs));
    }

    public function storeGroupAction(CreateGroupChat $request)
    {
        // create and store a new group with members including the creator
        DB::beginTransaction();
        $group = null;

        try {
            // create group
            $inputs = $request->all();
            $inputs['created_by_id'] = $this->me->id;
            $group = $this->chatGroup->store($inputs);

            if (!$group) {
                DB::rollBack();
                return $this->json('Failed to create a new group.');
            }

            $members = $request->get('members');
            if ($this->_isValidMembers($members)) {
                // add members
                foreach ($members as $user) {
                    if (!$this->_checkIsMember($group->id, $user) && User::where('id', $user)->first()) {
                        $this->chatGroupMember->store([
                                'chat_group_id' => $group->id, 'added_by_id' => $this->me->id, 'member_id' => $user]
                        );
                    }
                }

                // add your self
                $this->chatGroupMember->store([
                    'chat_group_id' => $group->id, 'added_by_id' => $this->me->id, 'member_id' => $this->me->id, 'is_admin' => 1
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->json('Failed to create a new group, reason: ' . $e->getMessage());
        }

        DB::commit();
        return $this->json($group);
    }

    private function _isValidMembers($members)
    {
        if (!$members) {
            return false;
        }

        if (!is_array($members)) {
            return false;
        }

        if (!count($members)) {
            return false;
        }

        return true;
    }

    public function updateGroupAction(UpdateGroupChat $request)
    {
        $group = $this->chatGroup->single($request->get('id'));

        if (!$group) {
            return $this->json('Group chat is not found.', 404);
        }

        if (!$this->_checkIsMember($group->id, $this->me->id)) {
            return $this->json('You are not a member of this group.', 422);
        }

        $members = $request->get('members');
        if ($this->_isValidMembers($members)) {
            // new members (everyone can add new members)
            foreach ($members as $user) {
                if (!$this->_checkIsMember($group->id, $user) && User::where('id', $user)->first()) {
                    $this->chatGroupMember->store(['chat_group_id' => $group->id,
                        'added_by_id' => $this->me->id, 'member_id' => $user]);
                }
            }

            // remove members (admin only)
            // you can't remove group creator
            if ($group->is_admin) {
                foreach ($group->members as $member) {
                    $isFound = false;

                    foreach ($members as $user) {
                        if ($member->member_id == $user) {
                            $isFound = true;
                        }
                    }

                    if ($isFound === false && $member->member_id !== $this->me->id) {
                        $this->chatGroupMember->remove(null, ['member_id' => $member->member_id, 'chat_group_id' => $group->id]);
                    }
                }
            }
        }

        // update group
        $this->chatGroup->edit($group->id, ['name' => $request->get('name')]);

        return $this->json($this->chatGroup->single($request->get('id')));
    }

    public function leaveGroupAction($group_id)
    {
        $group = $this->chatGroup->single($group_id);

        if (!$group) {
            return $this->json('Group chat is not found.', 404);
        }

        // we do not allow to leave a group creator
        if ($group->created_by_id === $this->me->id) {
            return $this->json('Unable to leave the group.', 422);
        }

        // leave a group
        return $this->_isMember($group_id,
            $this->chatGroupMember->remove(null, ['chat_group_id' => $group_id, 'member_id' => $this->me->id]));
    }

    public function deleteConversation($group_id)
    {
        $group = $this->chatGroup->single($group_id);

        if (!$group) {
            return $this->json('Group chat is not found.', 404);
        }

        // delete messages
        return $this->_isMember($group_id,
            $this->chatMessage->remove(null, ['chat_group_id' => $group_id, 'user_id' => $this->me->id]));
    }

    public function addToGroupAction($group_id, $member_id)
    {
        // add a new user to group
        return $this->json($this->chatGroupMember->store(['chat_group_id' => $group_id, 'member_id' => $member_id,
            'added_by_id' => $this->me->id]));
    }

    public function makeAdminAction($group_id, $member_id)
    {
        // is member of this group if not add a new member
        if (!$this->_checkIsMember($group_id, $member_id)) {
            return $this->json($this->chatGroupMember->store(['chat_group_id' => $group_id, 'member_id' => $member_id,
                'added_by_id' => $this->me->id, 'is_admin' => 1]));
        }

        // update is admin
        return $this->json($this->chatGroupMember->edit(null, ['is_admin' => 1],
            ['chat_group_id' => $group_id, 'member_id' => $member_id]));
    }

    public function removeAdminAction($group_id, $member_id)
    {
        $group = $this->chatGroup->single($group_id);

        if (!$group) {
            return $this->json('Group chat is not found.', 404);
        }

        // group chat creator can not be remove as admin
        if ($group->created_by_id === $member_id) {
            return $this->json('Group chat is creator is can not be remove as admin.', 422);
        }

        // only admin can remove another admin
        if (!$this->chatGroupMember->where('member_id', $this->me->id)->where('is_admin', 1)->first()) {
            return $this->json('Only a group admin can remove another admin.', 422);
        }

        return $this->_isMember($group_id, $this->chatGroupMember->edit(null, ['is_admin' => 0],
            ['chat_group_id' => $group_id, 'member_id' => $member_id]));
    }
}
