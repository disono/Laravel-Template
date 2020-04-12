<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Admin\Application;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Application\File\CSVImport;
use App\Models\CSV\User\UsersExport;
use App\Models\CSV\User\UsersImport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CSVController extends Controller
{
    protected $viewType = 'admin';

    public function __construct()
    {
        parent::__construct();
        $this->theme = 'application.csv';
    }

    /**
     * Import view
     *
     * @return JsonResponse|Response
     */
    public function csvImportAction()
    {
        $this->_isValidSource();
        $this->setHeader('title', 'Import CSV File (' . ucfirst($this->request->get('source')) . ')');
        return $this->view('import', $this->_link());
    }

    private function _isValidSource()
    {
        $sources = ['users'];

        if (!in_array($this->request->get('source'), $sources)) {
            abort(404);
        }
    }

    private function _link()
    {
        return ['link' => $this->_linkCreator()];
    }

    private function _linkCreator()
    {
        if ($this->request->get('source') === 'users') {
            return route('admin.user.browse');
        }

        return null;
    }

    /**
     * Import data
     *
     * @param CSVImport $request
     * @return bool
     */
    public function csvImportStoreAction(CSVImport $request)
    {
        if ($request->get('source') === 'users') {
            Excel::import(new UsersImport, $request->file('csv'));
        }

        return $this->redirect();
    }

    /**'
     * Export Data
     *
     * @return bool|Response|BinaryFileResponse
     */
    public function csvExportAction()
    {
        if ($this->request->get('source') === 'users') {
            return (new UsersExport($this->request->all()))->download('users.xlsx');
        }

        return $this->redirect();
    }

    /**
     * Download template
     *
     * @return bool|Response|BinaryFileResponse
     */
    public function csvTemplateAction()
    {
        if ($this->request->get('source') === 'users') {
            return (new UsersExport($this->request->all(), true))->download('users.xlsx');
        }

        return $this->redirect();
    }

}
