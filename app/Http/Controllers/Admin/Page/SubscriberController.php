<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Admin\Page;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;

class SubscriberController extends Controller
{
    public function __construct()
    {
        $this->view = 'page.';
        $this->view_type = 'admin';
        parent::__construct();
    }

    /**
     * List of subscribers
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $this->title = 'Subscribers';
        $this->content['subscriber'] = Subscriber::fetch(request_options([
            'search'
        ]));
        $this->content['subscriber_object'] = Subscriber::getAll(['object' => true]);
        return $this->response('subscriber');
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
        Subscriber::remove($id);

        if (request()->ajax()) {
            return success_json_response('Successfully deleted subscriber.');
        }

        return redirect()->back();
    }
}
