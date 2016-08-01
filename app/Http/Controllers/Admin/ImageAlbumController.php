<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: http://www.webmons.com
 * License: Apache 2.0
 */

namespace App\Http\Controllers\Admin;

use App\ImageAlbum;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ImageAlbumController extends Controller
{
    /**
     * List data
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $content['title'] = app_title('Albums');
        $content['albums'] = ImageAlbum::get();
        $content['request'] = $request;

        return admin_view('album.index', $content);
    }

    /**
     * Create new data
     *
     * @return mixed
     */
    public function create()
    {
        $content['title'] = app_title('Create Album');
        return admin_view('album.create', $content);
    }
    /**
     * Store new data
     *
     * @param Requests\Admin\AlbumStore $request
     * @return mixed
     */
    public function store(Requests\Admin\AlbumStore $request)
    {
        $id = ImageAlbum::store($request->all());

        return redirect('admin/album/upload/create/' . $id);
    }

    /**
     * Edit data
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $content['title'] = app_title('Edit Album');
        $data = ImageAlbum::single($id);
        if (!$data) {
            abort(404);
        }
        $content['album'] = $data;
        
        return admin_view('album.edit', $content);
    }
    /**
     * Update data
     *
     * @param Requests\Admin\AlbumUpdate $request
     * @return mixed
     */
    public function update(Requests\Admin\AlbumUpdate $request)
    {
        ImageAlbum::edit($request->get('id'), $request->all());

        return redirect('admin/albums');
    }

    /**
     * Delete data
     *
     * @param $id
     * @return mixed
     */
    public function ajaxDestroy($id)
    {
        ImageAlbum::remove($id);

        return success_json_response('Successfully deleted album.');
    }
}
