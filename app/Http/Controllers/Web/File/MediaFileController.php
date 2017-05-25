<?php

namespace App\Http\Controllers\Web\File;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\MediaFile;

class MediaFileController extends Controller
{
    public function __construct()
    {
        $this->response_type = 'json';
        parent::__construct();
    }

    /**
     * List of images
     */
    public function index()
    {
        if ($this->get('request_type') == 'images') {
            $this->content = $this->_uploadedImages();
        } else if ($this->get('request_type') == 'files') {
            $this->content = $this->_mediaFiles();
        }

        return $this->response();
    }

    /**
     * Upload image
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function upload()
    {
        if ($this->get('request_type') == 'images') {
            $this->_upload_image();
        } else if ($this->get('request_type') == 'files') {
            $this->_upload_file();
        }

        return $this->index();
    }

    /**
     * Upload image
     */
    private function _upload_image()
    {
        $me = me();
        upload_image($this->request->file('image'), [
            'user_id' => $me->id,
            'title' => $me->full_name,
            'type' => 'any',
            'crop_auto' => true
        ]);
    }

    /**
     * Upload image
     */
    private function _upload_file()
    {
        $me = me();
        $file = $this->request->file('file');
        $filename = upload_any_file($file);

        if ($filename && $file) {
            $ext = $file->getClientOriginalExtension();

            $category = 'others';
            if (in_array($ext, ['mp4'])) {
                $category = 'video';
            } else if (in_array($ext, ['doc', 'docx', 'pdf'])) {
                $category = 'document';
            } else if (in_array($ext, ['zip'])) {
                $category = 'zip';
            }

            MediaFile::store([
                'user_id' => $me->id,
                'title' => $this->get('title'),
                'description' => $this->get('description'),
                'filename' => $filename,
                'type' => $ext,
                'category' => $category
            ]);
        }
    }

    /**
     * Images list
     */
    private function _uploadedImages()
    {
        $me = me();
        $options = [];
        if ($me->role != 'admin') {
            $options['user_id'] = $me->id;
        }

        return Image::get(request_options([
            'type', 'search'
        ], $options));
    }

    /**
     * Files list (video, document, zip)
     */
    private function _mediaFiles()
    {
        $me = me();
        $options = [];
        if ($me->role != 'admin') {
            $options['user_id'] = $me->id;
        }

        return MediaFile::get(request_options([
            'type', 'category', 'search'
        ], $options));
    }
}
