<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Module\Application;

use App\Http\Controllers\Controller;

class ViewController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function viewAction($type)
    {
        if ($type == 'delete') {
            return theme('modals.delete');
        } else if ($type == 'fileExplorer') {
            return theme('modals.fileExplorer');
        } else if ($type == 'chatLeaveGroup') {
            return theme('modals.chatLeaveGroup');
        } else if ($type == 'chatDeleteConversation') {
            return theme('modals.chatDeleteConversation');
        } else if ($type == 'reportPage') {
            return theme('modals.reportPage');
        } else if ($type == 'profileMenu') {
            return theme('modals.profileMenu');
        }

        return abort(404);
    }
}
