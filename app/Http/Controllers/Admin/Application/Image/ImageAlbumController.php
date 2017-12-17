<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Admin\Application\Image;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\ImageAlbum;
use Illuminate\Http\Request;

/**
 * @property string view
 * @property string view_type
 */
class ImageAlbumController extends Controller
{
    public function __construct()
    {
        $this->view = 'album.';
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
        $this->title = 'Albums';
        $this->content['albums'] = ImageAlbum::fetch();
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
        $this->title = 'Create Albums';
        return $this->response('create');
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
        return $this->redirectResponse('admin/album/upload/create/' . $id);
    }

    /**
     * Edit data
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $data = ImageAlbum::single($id);
        if (!$data) {
            abort(404);
        }

        $this->title = 'Edit Album';
        $this->content['album'] = $data;
        return $this->response('edit');
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
        return $this->redirectResponse('admin/albums');
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
        ImageAlbum::remove($id);
        return success_json_response('Successfully deleted album.');
    }
}
