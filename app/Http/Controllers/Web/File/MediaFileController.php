<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Web\File;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Media\MediaFileStore;
use App\Models\MediaFile;

class MediaFileController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->response_type = 'json';
    }

    /**
     * List files
     *
     * @return bool|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index()
    {
        $options = [];
        if (!$this->me) {
            return $this->failed_response('Unable to list files.', 403);
        }

        if ($this->me->role != 'admin') {
            $options['user_id'] = $this->me->id;
        }

        $this->content = MediaFile::fetch(request_options('search|file_type|file_ext', $options));
        return $this->response();
    }

    /**
     * Upload new file
     *
     * @param MediaFileStore $request
     * @return bool|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function store(MediaFileStore $request)
    {
        $inputs = $request->all();
        $inputs['user_id'] = authenticated_id();
        $inputs['file'] = $request->file('file');
        $this->content = MediaFile::single(MediaFile::store($inputs));
        return $this->response();
    }

    /**
     * Delete file
     *
     * @param $id
     * @return bool|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \Exception
     */
    public function destroy($id)
    {
        $this->content = MediaFile::remove($id);
        return $this->response();
    }
}
