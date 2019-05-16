<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Admin\Application;

use App\Http\Requests\Admin\Application\Report\ReportMessageStore;
use App\Models\App\PageReport;
use App\Http\Controllers\Controller;
use App\Models\App\PageReportMessage;

class SubmittedReportController extends Controller
{
    protected $viewType = 'admin';
    private $pageReport = null;
    private $pageReportMessage = null;

    public function __construct()
    {
        parent::__construct();
        $this->theme = 'application.report.submitted';
        $this->pageReport = new PageReport();
        $this->pageReportMessage = new PageReportMessage();
    }

    public function indexAction()
    {
        $this->setHeader('title', 'Reports');
        return $this->view('index', [
            'reports' => $this->pageReport->fetch(requestValues('search'))
        ]);
    }

    public function showAction($id)
    {
        $data = $this->pageReport->single($id);
        if (!$data) {
            abort(404);
        }

        $this->setHeader('title', $data->page_report_reason_name);
        return $this->view('show', [
            'report' => $data, 'statuses' => $this->pageReport->statuses(),
            'messages' => $this->pageReportMessage->fetch(['page_report_id' => $data->id])
        ]);
    }

    public function messageAction(ReportMessageStore $request)
    {
        $inputs = $request->all();
        $inputs['user_id'] = __me()->id;
        $this->pageReportMessage->store($inputs);
        return $this->json(['redirect' => '/admin/submitted-report/show/' . $request->get('page_report_id')]);
    }

    public function statusAction($id, $status)
    {
        $report = $this->pageReport->single($id);
        if (!$report) {
            abort(404);
        }

        $inputs = ['status' => $status];
        if (!$report->responded_by) {
            $inputs['responded_by'] = __me()->id;
        }

        $this->pageReport->edit($id, $inputs);
        return $this->redirect();
    }

    public function destroyAction($id)
    {
        $this->pageReport->remove($id);
        return $this->json('Report is successfully deleted.');
    }
}
