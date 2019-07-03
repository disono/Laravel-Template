<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\Vendor\Facades\ActivityLog;

class ActivityLogController extends Controller
{
    protected $viewType = 'admin';
    private $_activityLog;

    public function __construct()
    {
        parent::__construct();
        $this->theme = 'user.activity_log';
        $this->_activityLog = ActivityLog::self();
    }

    public function indexAction()
    {
        $this->setHeader('title', 'Activity Logs');
        $this->_activityLog->enableSearch = true;
        return $this->view('index', [
            'logs' => $this->_activityLog->fetch(requestValues('search|pagination_show|user_id|full_name|table_name|reason'))
        ]);
    }

    public function detailsAction($id)
    {
        $activityLog = $this->_activityLog->single($id);
        if (!$activityLog) {
            abort(404);
        }

        $this->setHeader('title', 'Activity Log (' . $activityLog->table_name . ')');
        return $this->view('details', ['details' => $activityLog]);
    }

    public function destroyAction($id)
    {
        $this->_activityLog->remove($id);
        return $this->json('Activity log is successfully deleted.');
    }
}
