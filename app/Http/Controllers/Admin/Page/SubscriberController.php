<?php

namespace App\Http\Controllers\Admin\Page;

use App\Models\Subscriber;
use App\Http\Controllers\Controller;

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
        $this->content['subscriber'] = Subscriber::get(request_options([
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
