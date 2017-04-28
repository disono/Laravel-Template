<?php

namespace App\Http\Controllers\Web\File;

use App\Http\Controllers\Controller;
use App\Models\Image;

class ImageController extends Controller
{
    /**
     * List of images
     */
    public function index()
    {
        $me = me();
        $this->response_type = 'json';

        $options = [];
        if ($me->role != 'admin') {
            $options['user_id'] = $me->id;
        }

        $this->content = Image::get(request_options([
            'type', 'search'
        ], $options));

        return $this->response();
    }

    /**
     * Upload image
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function upload()
    {
        $me = me();
        upload_image($this->request->file('image'), [
            'user_id' => $me->id,
            'title' => $me->full_name,
            'type' => 'any',
            'crop_auto' => true
        ]);

        return $this->index();
    }
}
