<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BaseController extends Controller
{

    protected $modelName;
    protected $dataName;

    protected $indexData = [];
    protected $showData = [];
    protected $createData = [];
    protected $storeData = [];
    protected $editData = [];
    protected $updateData = [];

    // failed messages
    protected $failedCreation = 'Failed to create new data.';
    protected $failedUpdate = 'Failed to update data.';

    // search parameters
    protected $requestValuesSearch = '';
    protected $requestValuesDefault = [];
    protected $indexEnableSearch = false;

    protected $allowShow = false;
    protected $allowCreate = false;
    protected $allowEdit = false;
    protected $allowDelete = false;

    protected $indexTitle = null;
    protected $showTitle = null;
    protected $createTitle = null;
    protected $editTitle = null;

    // redirect url after updating or creating a new data
    protected $afterRedirectUrl = '/';

    // adding user's id before storing new data (column name)
    protected $hasOwner = null;

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
        $this->beforeIndex();
        $this->setHeader('title', $this->indexTitle);

        $this->modelName->enableSearch = $this->indexEnableSearch;
        $this->indexData[$this->dataName] = $this->modelName->fetch(requestValues($this->requestValuesSearch, $this->requestValuesDefault));

        $this->afterIndex();
        return $this->view('index', $this->indexData);
    }

    protected function beforeIndex()
    {
        // before showing the list of data
    }

    protected function afterIndex()
    {
        // after preparing the data
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

        $this->beforeShow($id);
        $this->setHeader('title', $this->showTitle);

        $this->showData[$this->dataName] = $this->modelName->single($id);
        if (!$this->showData[$this->dataName]) {
            abort(404);
        }

        $this->afterShow();
        return $this->view('show', $this->showData);
    }

    protected function beforeShow($id)
    {
        // before preparing the data to show
    }

    protected function afterShow()
    {
        // after preparing the data to show
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

        $this->afterCreate();
        return $this->view('create', $this->createData);
    }

    protected function beforeCreate()
    {
        // before preparing the data to view
    }

    protected function afterCreate()
    {
        // after preparing the data to view
    }

    /**
     * Store new data
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function storeAction(Request $request)
    {
        return $this->processStore($request);
    }

    protected function processStore($request)
    {
        if (!$this->allowCreate) {
            abort(401);
        }

        $this->storeData = $request->all();

        // add the creator (User)
        if ($this->hasOwner !== null) {
            $this->storeData[$this->hasOwner] = __me()->id;
        }

        $this->beforeStore();
        if (!$this->modelName->store($this->storeData)) {
            return $this->json(['name' => $this->failedCreation], 422, false);
        }

        $this->afterStore();
        return $this->json(['redirect' => $this->afterRedirectUrl]);
    }

    protected function beforeStore()
    {
        // before creating the new data
    }

    protected function afterStore()
    {
        // after creating the new data
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
        $this->editData[$this->dataName] = $this->modelName->single($id);
        if (!$this->editData[$this->dataName]) {
            abort(404);
        }

        $this->afterEdit();
        return $this->view('edit', $this->editData);
    }

    protected function beforeEdit()
    {
        // before editing view showed
    }

    protected function afterEdit()
    {
        // after getting the data to view
    }

    /**
     * Update data
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateAction(Request $request)
    {
        return $this->processUpdate($request);
    }

    protected function processUpdate($request)
    {
        if (!$this->allowEdit) {
            abort(401);
        }

        $this->updateData = $request->all();
        $this->beforeUpdate();

        if (!$this->modelName->edit($request->get('id'), $this->updateData)) {
            return $this->json(['name' => $this->failedUpdate], 422, false);
        }

        $this->afterUpdate();
        return $this->json(['redirect' => $this->afterRedirectUrl]);
    }

    protected function beforeUpdate()
    {
        // before updating the data
    }

    protected function afterUpdate()
    {
        // after updating the data
    }

    /**
     * Delete data
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroyAction($id)
    {
        if (!$this->allowDelete) {
            abort(401);
        }

        $this->beforeDestroy();
        $this->modelName->remove($id);
        $this->afterDestroy();

        return $this->json('Data is successfully deleted.');
    }

    protected function beforeDestroy()
    {
        // before destroying the data
    }

    protected function afterDestroy()
    {
        // after destroying the data
    }

}