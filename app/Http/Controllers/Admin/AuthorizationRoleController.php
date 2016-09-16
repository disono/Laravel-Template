<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
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

        $content['authorization'] = Authorization::get([
            'all' => true,
            'search' => $request->get('search')
        ]);

        $authorization_roles = AuthorizationRole::get([
            'role_id' => $role_id,
            'all' => true
        ]);
        $content['authorization_roles'] = db_filter_id($authorization_roles, 'authorization_id');

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
        $authorization_filtered_id = db_filter_id(Authorization::get([
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

        return redirect()->back()->withInput($request->all());
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
