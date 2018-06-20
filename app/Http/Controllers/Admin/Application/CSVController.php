<?php
/**
 * @author          Archie, Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (webmons.com), 2016-2018
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Admin\Application;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Application\File\CSVImport;
use App\Models\CSV\UserCSV;

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
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function csvImportAction()
    {
        $this->_isValidSource();
        $this->setHeader('title', 'Import CSV File (' . ucfirst($this->request->get('source')) . ')');
        return $this->view('import', $this->_link());
    }

    /**
     * Import data
     *
     * @param CSVImport $request
     * @return mixed
     */
    public function csvImportStoreAction(CSVImport $request)
    {
        $this->_isValidSource();
        try {
            $this->_processImport($request);
        } catch (\Exception $e) {
            return $this->redirect()->with('error_message', $e->getMessage());
        }

        return $this->redirect()->with('success', 'Successfully Uploaded the CSV file.');
    }

    /**
     * Export data
     *
     * @return bool
     */
    public function csvExportAction()
    {
        $this->_isValidSource();
        return $this->_exporter();
    }

    /**
     * Download template
     *
     * @return bool
     */
    public function csvTemplateAction()
    {
        $this->_isValidSource();
        return $this->_templates();
    }

    private function _isValidSource()
    {
        $sources = ['users', 'pages', 'page_categories'];
        if (!in_array($this->request->get('source'), $sources)) {
            abort(404);
        }
    }

    private function _processImport($request)
    {
        if ($request->get('source') === 'users') {
            $this->_importer($this->request->file('csv')->getPathName());
        }
    }

    private function _importer($file)
    {
        (new UserCSV())->import($file);
    }

    private function _exporter()
    {
        if ($this->request->get('source') === 'users') {
            return (new UserCSV($this->request->get('params')))->download('users');
        }

        return $this->redirect();
    }

    private function _templates()
    {
        if ($this->request->get('source') === 'users') {
            return (new UserCSV($this->request->get('params')))->template();
        }

        return $this->redirect();
    }

    private function _link()
    {
        return ['link' => $this->_linkCreator()];
    }

    private function _linkCreator()
    {
        if ($this->request->get('source') === 'users') {
            return route('admin.user.index');
        }

        return null;
    }
}
