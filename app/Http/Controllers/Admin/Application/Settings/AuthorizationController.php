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
use App\Models\AuthHistory;
use App\Models\Authorization;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class AuthorizationController extends Controller
{
    public function __construct()
    {
        $this->view = 'authorization.';
        $this->view_type = 'admin';
        parent::__construct();
    }

    /**
     * List off data
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $this->title = 'Authorization';
        $this->content['authorizations'] = Authorization::fetch();
        $this->content['request'] = $request;
        return $this->response('index');
    }

    /**
     * Authentication history
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getHistory(Request $request)
    {
        $options = [];

        if ($request->get('user_id')) {
            $options['user_id'] = $request->get('user_id');
        }

        $this->title = 'Authorization Histories';
        $this->content['authorization_histories'] = AuthHistory::fetch($options);
        $this->content['request'] = $request;
        return $this->response('history');
    }

    /**
     * Create new data
     *
     * @return mixed
     */
    public function create()
    {
        $this->title = 'Create Authorization';
        $this->content['route_names'] = access_routes();
        return $this->response('create');
    }

    /**
     * Store new data
     *
     * @param Requests\Admin\AuthorizationStore $request
     * @return mixed
     */
    public function store(Requests\Admin\AuthorizationStore $request)
    {
        Authorization::store($request->all());
        return $this->redirectResponse('admin/authorizations');
    }

    /**
     * Edit data
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $data = Authorization::single($id);
        if (!$data) {
            abort(404);
        }

        $this->title = 'Edit Authorization';
        $this->content['route_names'] = access_routes();
        $this->content['authorization'] = $data;
        return $this->response('edit');
    }

    /**
     * Update data
     *
     * @param Requests\Admin\AuthorizationUpdate $request
     * @return mixed
     */
    public function update(Requests\Admin\AuthorizationUpdate $request)
    {
        Authorization::edit($request->get('id'), $request->all());
        return redirect('admin/authorizations');
    }

    /**
     * Reset all authorization
     *
     * @return bool
     */
    public function reset()
    {
        DB::table('authorizations')->truncate();
        DB::table('authorization_roles')->truncate();

        // routes
        foreach (Route::getRoutes() as $value) {
            $name = $value->getName();
            if ($name) {
                DB::table('authorizations')->insert([
                    'name' => ucwords(str_replace('_', ' ', str_replace('-', ' ', $name))),
                    'identifier' => $name,
                    'created_at' => sql_date()
                ]);
            }
        }

        // authorization role (administrator)
        $authorization = DB::table('authorizations');
        $roles = ['admin', 'employee', 'cashier', 'supplier', 'tel_agent'];

        foreach ($roles as $role) {
            $find_role = Role::where('slug', $role)->first();

            if ($find_role) {
                foreach ($authorization->get() as $row) {
                    DB::table('authorization_roles')->insert([
                        'role_id' => $find_role->id,
                        'authorization_id' => $row->id,
                        'created_at' => sql_date()
                    ]);
                }
            }
        }

        return $this->redirectResponse('admin/authorizations');
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
        Authorization::remove($id);

        if (request()->ajax()) {
            return success_json_response('Successfully deleted authorization.');
        }

        return $this->redirectResponse();
    }
}
