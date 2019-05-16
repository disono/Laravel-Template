<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class BaseController extends Controller
{

    protected $modelName;
    protected $dataName;

    protected $data = null;
    protected $requestValuesSearch = '';
    protected $requestValuesDefault = [];

    protected $allowShow = false;
    protected $allowCreate = false;
    protected $allowEdit = false;
    protected $allowDelete = false;

    protected $indexTitle = null;
    protected $showTitle = null;
    protected $createTitle = null;
    protected $editTitle = null;

    protected $afterCreateRedirectUrl = '/';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * List of data
     *
     * @return JsonResponse|Response
     */
    public function indexAction()
    {
        $this->beforeIndexAction();
        $this->setHeader('title', $this->indexTitle);

        return $this->view('index',
            array_merge($this->data, requestValues($this->requestValuesSearch, $this->requestValuesDefault)));
    }

    protected function beforeIndexAction()
    {

    }

    /**
     * Show details of data
     *
     * @param null $id
     * @return JsonResponse|Response
     */
    public function showAction($id = null)
    {
        if (!$this->allowShow) {
            abort(404);
        }

        $this->beforeShowAction($id);
        $this->setHeader('title', $this->showTitle);

        $this->data = $this->modelName->single($id);
        if (!$this->data) {
            abort(404);
        }

        return $this->view('show', [$this->dataName => $this->data]);
    }

    protected function beforeShowAction($id)
    {

    }

    /**
     * Create a new data
     *
     * @return JsonResponse|Response
     */
    public function createAction()
    {
        if (!$this->allowCreate) {
            abort(401);
        }

        $this->beforeCreate();
        $this->setHeader('title', $this->createTitle);
        return $this->view('create', $this->data);
    }

    protected function beforeCreate()
    {

    }

    /**
     * Store new data
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function storeAction(Request $request)
    {
        if (!$this->allowCreate) {
            abort(401);
        }

        $this->data = $request->all();
        $this->beforeStoreAction();

        if (!$this->modelName->store($this->data)) {
            return $this->json(['name' => 'Failed to crate a new data.'], 422, false);
        }

        $this->afterStoreAction();

        return $this->json(['redirect' => $this->afterCreateRedirectUrl]);
    }

    protected function beforeStoreAction()
    {

    }

    protected function afterStoreAction()
    {

    }

    /**
     * Edit view
     *
     * @param $id
     * @return JsonResponse|Response
     */
    public function editAction($id)
    {
        if (!$this->allowEdit) {
            abort(401);
        }

        $this->beforeEdit();
        $this->setHeader('title', $this->editTitle);
        $this->data[$this->dataName] = $this->modelName->single($id);
        if (!$this->data[$this->dataName]) {
            abort(404);
        }

        return $this->view('edit', $this->data);
    }

    protected function beforeEdit()
    {

    }

    /**
     * Update data
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateAction(Request $request)
    {
        if (!$this->allowEdit) {
            abort(401);
        }

        $this->data = $request->all();
        $this->beforeUpdateAction();

        if (!$this->modelName->edit($request->get('id'), $this->data)) {
            return $this->json(['name' => 'Failed to update.'], 422, false);
        }

        $this->afterUpdateAction();

        return $this->json(['redirect' => $this->afterCreateRedirectUrl]);
    }

    protected function beforeUpdateAction()
    {

    }

    protected function afterUpdateAction()
    {

    }

    /**
     * Delete data
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        if (!$this->allowDelete) {
            abort(401);
        }

        $this->modelName->remove($id);
        return $this->json('Data is successfully deleted.');
    }

}