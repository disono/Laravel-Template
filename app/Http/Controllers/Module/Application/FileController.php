<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Module\Application;

use App\Http\Controllers\Controller;
use App\Http\Requests\Module\Application\FileCreate;
use App\Http\Requests\Module\Application\FileUpdate;
use App\Models\File;
use Intervention\Image\Facades\Image;

class FileController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function indexAction()
    {
        $default = [];
        if (__me()->role === 'client') {
            $default['user_id'] = __me()->id;
        }
        return $this->json((new File())->fetch(requestValues('search|type', $default)));
    }

    public function streamVideoAction($file)
    {
        return videoStream('private/' . $file);
    }

    public function streamAudioAction($file)
    {
        return videoStream('private/' . $file);
    }

    public function streamImageAction($file)
    {
        try {
            // create a new image resource
            $img = $img = Image::make('private/' . $file);

            // resize image
            if ($this->request->get('width') && !$this->request->get('height')) {
                $img->fit($this->_imageSize('width'));
            } else if ($this->request->get('width') && $this->request->get('height')) {
                $img->fit($this->_imageSize('width'), $this->_imageSize('height'));
            }

            // send HTTP header and output image data
            if ($this->request->get('encode') === 'base64') {
                return $img->encode('data-url', $this->_imageQuality());
            } else {
                return $img->response('jpg', $this->_imageQuality());
            }
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    private function _imageSize($key)
    {
        return (int)$this->request->get($key);
    }

    private function _imageQuality()
    {
        $quality = $this->request->get('quality', 75);
        if ($quality <= 0) {
            return 45;
        }

        if ($quality > 100) {
            return 100;
        }

        return $quality;
    }

    public function createAction(FileCreate $request)
    {
        $file = fileUpload([
            'file' => $request->file('file_selected'),
            'destination' => 'private',
            'title' => $request->get('title'),
            'desc' => $request->get('description')
        ]);

        if (!count($file)) {
            if ($file[0]->db === null) {
                return $this->json(['file_selected' => 'Failed to save the file.'], 422);
            }
        }

        return $this->json($file[0]->db);
    }

    public function updateAction(FileUpdate $request)
    {
        (new File())->edit($request->get('id'), $request->all());
        return $this->json('File is updated successfully.');
    }

    public function destroyAction($id)
    {
        File::destroy($id);
        return $this->json('File is deleted successfully.');
    }
}
