<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Admin\Application\Settings;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;

/**
 * @property string view
 * @property string view_type
 */
class ActivityLogController extends Controller
{
    public function __construct()
    {
        $this->view = 'activity-log.';
        $this->view_type = 'admin';
        parent::__construct();
    }

    /**
     * List of activity logs
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $source_id = $this->get('source_id');
        $source_type = $this->get('source_type');

        $this->title = 'Activity Logs';
        $this->content['logs'] = ActivityLog::logs($source_id, $source_type, request_options([
            'search', 'source_type'
        ]));

        return $this->response('index');
    }
}
