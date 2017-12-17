<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Admin\Application\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->view = 'role.';
        $this->view_type = 'admin';
        parent::__construct();
    }

    /**
     * List data
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $this->title = 'Roles';
        $this->content['roles'] = Role::fetch();
        $this->content['request'] = $request;
        return $this->response('index');
    }

    /**
     * Create new data
     *
     * @return mixed
     */
    public function create()
    {
        $this->title = 'Create Role';
        return $this->response('create');
    }

    /**
     * Store new data
     *
     * @param Requests\Admin\RoleStore $request
     * @return mixed
     */
    public function store(Requests\Admin\RoleStore $request)
    {
        Role::store($request->all());
        return $this->redirectResponse('admin/roles');
    }

    /**
     * Edit data
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $data = Role::single($id);
        if (!$data) {
            abort(404);
        }

        $this->title = 'Edit Role';
        $this->content['role'] = $data;
        return $this->response('edit');
    }

    /**
     * Update data
     *
     * @param Requests\Admin\RoleUpdate $request
     * @return mixed
     */
    public function update(Requests\Admin\RoleUpdate $request)
    {
        Role::edit($request->get('id'), $request->all());
        return $this->redirectResponse('admin/roles');
    }

    /**
     * Delete data
     *
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function destroy($id)
    {
        $allowed = Role::remove($id);

        if (request()->ajax()) {
            if (!$allowed) {
                return failed_json_response('This resource is not allowed to delete.');
            }

            return success_json_response('Successfully deleted role.');
        }

        return redirect()->back();
    }
}
