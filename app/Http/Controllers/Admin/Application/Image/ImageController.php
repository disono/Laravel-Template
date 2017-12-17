<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Admin\Application\Image;

use App\Http\Controllers\Controller;
use App\Models\Image;

class ImageController extends Controller
{
    public function __construct()
    {
        $this->view = 'image.';
        $this->view_type = 'admin';
        parent::__construct();
    }

    /**
     * List of data
     *
     * @return mixed
     */
    public function index()
    {
        $this->title = app_title('Settings');
        $this->content['images'] = Image::fetch();
        return $this->response('index');
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
        Image::remove($id);

        if (request()->ajax()) {
            return success_json_response('Successfully deleted image.');
        }

        return $this->redirectResponse();
    }
}
