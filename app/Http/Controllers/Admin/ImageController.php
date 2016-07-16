<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: http://www.webmons.com
 * License: Apache 2.0
 */

namespace App\Http\Controllers\Admin;

use App\Image;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ImageController extends Controller
{
    /**
     * List of data
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $content['title'] = app_title('Settings');
        $content['images'] = Image::get();
        $content['request'] = $request;

        return admin_view('image.index', $content);
    }

    /**
     * Delete data
     *
     * @param $id
     * @return mixed
     */
    public function ajaxDestroy($id)
    {
        Image::remove($id);
        return success_json_response('Successfully deleted image.');
    }
}
