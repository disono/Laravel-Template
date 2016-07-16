<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: http://www.webmons.com
 * License: Apache 2.0
 */

namespace App\Http\Controllers\Admin;

use App\Authorization;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

class AuthorizationController extends Controller
{
    /**
     * List off data
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $content['title'] = app_title('Authorization');
        $content['authorizations'] = Authorization::get();
        $content['request'] = $request;
        
        return admin_view('authorization.index', $content);
    }
    
    /**
     * Create new data
     *
     * @return mixed
     */
    public function create()
    {
        $content['title'] = app_title('Create Authorization');
        $content['route_names'] = Route::getRoutes();

        return admin_view('authorization.create', $content);
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
        
        return redirect('admin/authorizations');
    }

    /**
     * Edit data
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $content['title'] = app_title('Edit Authorization');
        $content['route_names'] = Route::getRoutes();
        
        $data = Authorization::single($id);
        if (!$data) {
            abort(404);
        }
        
        $content['authorization'] = $data;
        return admin_view('authorization.edit', $content);
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
     * Delete data
     *
     * @param $id
     * @return mixed
     */
    public function ajaxDestroy($id)
    {
        Authorization::remove($id);

        return success_json_response('Successfully deleted authorization.');
    }
}
