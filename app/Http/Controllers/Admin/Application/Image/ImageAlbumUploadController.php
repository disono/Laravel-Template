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

class ImageAlbumUploadController extends Controller
{
    public function __construct()
    {
        $this->view = 'album-file.';
        $this->view_type = 'admin';
        parent::__construct();
    }

    /**
     * List of photos
     *
     * @param $album_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($album_id)
    {
        $data = ImageAlbum::single($album_id);
        if (!$data) {
            abort(404);
        }

        $this->title = app_title('Edit Role');
        $this->content['album'] = $data;
        return $this->response('create');
    }

    /**
     * Upload photos to album
     *
     * @param Requests\Admin\AlbumUpload $request
     * @return bool
     */
    public function store(Requests\Admin\AlbumUpload $request)
    {
        upload_image($request->file('image'), [
            'user_id' => auth()->user()->id,
            'source_id' => $request->get('album_id'),
            'title' => ucfirst($request->get('title')),
            'description' => ucfirst($request->get('description')),
            'type' => 'album'
        ]);

        return $this->redirectResponse();
    }
}
