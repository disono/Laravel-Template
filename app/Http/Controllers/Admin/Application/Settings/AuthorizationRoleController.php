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
use App\Models\Authorization;
use App\Models\AuthorizationRole;
use Illuminate\Http\Request;

class AuthorizationRoleController extends Controller
{
    public function __construct()
    {
        $this->view = 'authorization-role.';
        $this->view_type = 'admin';
        parent::__construct();
    }

    /**
     * List data
     *
     * @param Request $request
     * @param $role_id
     * @return mixed
     */
    public function index(Request $request, $role_id)
    {
        $authorization_roles = AuthorizationRole::fetch([
            'role_id' => $role_id,
            'all' => true
        ]);

        $this->title = 'Authorization';
        $this->content['authorization'] = Authorization::fetch([
            'all' => true,
            'search' => $request->get('search')
        ]);
        $this->content['authorization_roles'] = db_filter_id($authorization_roles, 'authorization_id');
        $this->content['request'] = $request;
        $this->content['role_id'] = $role_id;
        return $this->response('index');
    }

    /**
     * Store new data
     *
     * @param Requests\Admin\AuthorizationRoleStore $request
     * @return mixed
     */
    public function store(Requests\Admin\AuthorizationRoleStore $request)
    {
        $role_id = $request->get('role_id');
        $authorization_checked = [];

        // add
        foreach ($request->get('authorization_id') as $authorization_id) {
            $authorization_checked[] = $authorization_id;

            AuthorizationRole::store([
                'role_id' => $role_id,
                'authorization_id' => $authorization_id
            ]);
        }

        // filter unchecked id
        $found = [];
        $authorization_filtered_id = db_filter_id(Authorization::fetch([
            'role_id' => $role_id,
            'all' => true
        ]), 'id');
        foreach ($authorization_filtered_id as $authorization) {
            if (!in_array($authorization, $authorization_checked)) {
                $found[] = $authorization;
            }
        }

        // remove all unchecked id
        foreach ($found as $authorization) {
            AuthorizationRole::where('authorization_id', $authorization)->where('role_id', $role_id)->delete();
        }

        return $this->redirectResponse()->withInput($request->all());
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
        AuthorizationRole::remove($id);

        if (request()->ajax()) {
            return success_json_response('Successfully deleted authorization-role.');
        }

        return redirect()->back();
    }
}
