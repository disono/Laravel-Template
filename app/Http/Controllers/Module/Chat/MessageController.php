<?php
/**
 * @author          Archie, Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (webmons.com), 2016-2018
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Module\Chat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

        return $this->view('show');
    }

    public function showGroupAction($group_id)
    {
        // show messages on group with validation if current user is existing as member of the group
    }

    public function sendAction()
    {
        // send message to group
        // check if send to list of users or group id
    }

    public function storeGroupAction()
    {
        // create and store a new group with members including the creator
    }

    public function leaveGroupAction($user_id)
    {
        // leave a group
    }

    public function addToGroupAction($group_id, $user_id)
    {
        // add a new user to group
    }
}
