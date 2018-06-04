<?php
/**
 * @author          Archie, Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (webmons.com), 2016-2018
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Module\Application;

use App\Http\Controllers\Controller;
use App\Http\Requests\Module\Application\FileCreate;
use App\Http\Requests\Module\Application\FileUpdate;
use App\Models\File;

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
        return $this->json(File::fetch(requestValues('search|type', $default)));
    }

    public function createAction(FileCreate $request)
    {
        $file = fileUpload($request->file('file_selected'), 'private', [],
            $request->get('title'), $request->get('description'), null, 0);
        if (!count($file)) {
            if ($file[0]->db === null) {
                return $this->json(['file_selected' => 'Failed to save the file.'], 422);
            }
        }

        return $this->json($file[0]->db);
    }

    public function updateAction(FileUpdate $request)
    {
        File::edit($request->get('id'), $request->all());
        return $this->json('File is updated successfully.');
    }

    public function destroyAction($id)
    {
        File::destroy($id);
        return $this->json('File is deleted successfully.');
    }
}
