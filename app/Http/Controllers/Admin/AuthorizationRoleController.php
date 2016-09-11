<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Authorization;
use App\Models\AuthorizationRole;
use Illuminate\Http\Request;

class AuthorizationRoleController extends Controller
{
    /**
     * List data
     *
     * @param Request $request
     * @param $role_id
     * @return mixed
     */
    public function index(Request $request, $role_id)
    {
        $content['title'] = app_title('Authorization');
        $content['authorization_roles'] = AuthorizationRole::get([
            'role_id' => $role_id
        ]);
        $content['authorization_role_all'] = AuthorizationRole::get([
            'role_id' => $role_id,
            'all' => true
        ]);
        $content['authorizations'] = Authorization::getAll([
            'exclude' => ['key' => 'id', 'val' => db_filter_id($content['authorization_role_all'], 'authorization_id')]
        ]);
        $content['request'] = $request;
        $content['role_id'] = $role_id;

        return admin_view('authorization-role.index', $content);
    }

    /**
     * Store new data
     *
     * @param Requests\Admin\AuthorizationRoleStore $request
     * @return mixed
     */
    public function store(Requests\Admin\AuthorizationRoleStore $request)
    {
        AuthorizationRole::store($request->all());
        return redirect('admin/authorization-roles/' . $request->get('role_id'));
    }

    /**
     * Delete data
     *
     * @param $id
     * @return mixed
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
