<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Module\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Module\Application\Report\ReportMessageStore;
use App\Models\Vendor\Facades\PageReport;
use App\Models\Vendor\Facades\PageReportMessage;
use App\Models\Vendor\Facades\PageReportReason;

class PageReportController extends Controller
{
    private $pageReport = NULL;
    private $pageReportMessage = NULL;

    public function __construct()
    {
        parent::__construct();
        $this->theme = 'user.settings.report';
        $this->pageReport = PageReport::self();
        $this->pageReportMessage = PageReportMessage::self();
    }

    public function indexAction()
    {
        $this->setHeader('title', 'Page Reports');

        return $this->view('index', [
            'reports' => $this->pageReport->fetch(requestValues(
                'search|page_report_reason_id|status|created_at'
            ), ['user_id' => $this->me->id]),
            'reasons' => PageReportReason::all(),
            'statuses' => PageReport::statuses()
        ]);
    }

    public function showAction($id)
    {
        $data = $this->pageReport->single(['id' => $id, 'user_id' => $this->me->id]);
        if (!$data) {
            abort(404);
        }

        $this->setHeader('title', $data->page_report_reason_name);
        return $this->view('show', [
            'report' => $data,
            'statuses' => $this->pageReport->statuses(),
            'messages' => $this->pageReportMessage->fetch(['page_report_id' => $data->id])
        ]);
    }

    public function messageAction(ReportMessageStore $request)
    {
        $inputs = $request->all();
        $inputs['user_id'] = __me()->id;
        $this->pageReportMessage->store($inputs);
        return $this->json(['redirect' => '/page-report/show/' . $request->get('page_report_id')]);
    }

    public function statusAction($id)
    {
        $report = $this->pageReport->single($id);
        if (!$report) {
            abort(404);
        }

        $this->pageReport->edit($id, ['status' => 'Reopened']);
        return $this->redirect();
    }

    public function destroyAction($id)
    {
        if (__settings('allowDelPageReport')->value !== 'enabled') {
            return failedJSONResponse('Report is not allowed to delete', 498, false);
        }

        $this->pageReport->remove(['id' => $id, 'user_id' => __me()->id]);
        return $this->json('Report is successfully deleted.');
    }
}
