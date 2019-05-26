<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Admin\Application;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Application\Report\ReportReasonStore;
use App\Http\Requests\Admin\Application\Report\ReportReasonUpdate;
use App\Models\App\PageReportReason;

class ReportReasonController extends Controller
{
    protected $viewType = 'admin';
    private $reportReason;

    public function __construct()
    {
        parent::__construct();
        $this->theme = 'application.report.reasons';
        $this->reportReason = new PageReportReason();
    }

    public function indexAction()
    {
        $this->setHeader('title', 'Report Reasons');
        $this->reportReason->enableSearch = true;
        return $this->view('index', [
            'reasons' => $this->reportReason->fetch(requestValues('search|pagination_show|name'))
        ]);
    }

    public function createAction()
    {
        $this->setHeader('title', 'Add New Report Reason');
        return $this->view('create');
    }

    public function storeAction(ReportReasonStore $request)
    {
        $role = $this->reportReason->store($request->all());
        if (!$role) {
            return $this->json(['name' => 'Failed to crate a new role.'], 422, false);
        }

        return $this->json(['redirect' => '/admin/report-reasons']);
    }

    public function editAction($id)
    {
        $reason = $this->reportReason->single($id);
        if (!$reason) {
            abort(404);
        }

        $this->setHeader('title', 'Editing ' . $reason->name);
        return $this->view('edit', ['reason' => $reason]);
    }

    public function updateAction(ReportReasonUpdate $request)
    {
        $this->reportReason->edit($request->get('id'), $request->all());
        return $this->json('Report Reason is successfully updated.');
    }

    public function destroyAction($id)
    {
        if (!$this->reportReason->remove($id)) {
            return $this->json('Unable to remove report reason because is already used.', 422);
        }

        return $this->json('Report Reason is successfully deleted.');
    }
}
