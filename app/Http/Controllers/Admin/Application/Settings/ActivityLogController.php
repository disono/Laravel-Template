<?php

namespace App\Http\Controllers\Admin\Application\Settings;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;

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

        $this->content['logs'] = ActivityLog::get($source_id, $source_type, request_options([
            'search', 'source_type'
        ]));
        $this->title = 'Activity Logs';

        return $this->response('index');
    }
}
