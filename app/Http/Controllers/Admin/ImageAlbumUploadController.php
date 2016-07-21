<?php

namespace App\Http\Controllers\Admin;

use App\ImageAlbum;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ImageAlbumUploadController extends Controller
{
    /**
     * List of photos
     *
     * @param $album_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($album_id) 
    {
        $content['title'] = app_title('Edit Role');
        $data = ImageAlbum::single($album_id);
        if (!$data) {
            abort(404);
        }
        $content['album'] = $data;

        return admin_view('album-file.create', $content);
    }

    /**
     * Upload photos to album
     * 
     * @param Requests\Admin\AlbumUpload $request
     * @return \Illuminate\Http\RedirectResponse
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

        return redirect()->back();
    }
}
