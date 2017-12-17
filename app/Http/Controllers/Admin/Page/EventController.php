<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Admin\Page;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function __construct()
    {
        $this->view = 'event.';
        $this->view_type = 'admin';
        parent::__construct();
    }

    /**
     * List data
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $this->title = 'Events';
        $this->content['events'] = Event::fetch(request_options('search'));
        return $this->response('index');
    }

    /**
     * Create new data
     *
     * @return mixed
     */
    public function create()
    {
        $this->title = 'Create Event';
        return $this->response('create');
    }

    /**
     * Store new data
     *
     * @param Requests\Admin\EventStore $request
     * @return mixed
     */
    public function store(Requests\Admin\EventStore $request)
    {
        $inputs = $request->all();
        $inputs['image'] = $request->file('image');
        $inputs['user_id'] = auth()->user()->id;
        Event::store($inputs);
        return $this->redirectResponse('admin/events');
    }

    /**
     * Edit data
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $data = Event::single($id);
        if (!$data) {
            abort(404);
        }

        $this->title = 'Edit Event';
        $this->content['event'] = $data;
        return $this->response('edit');
    }

    /**
     * Update data
     *
     * @param Requests\Admin\EventUpdate $request
     * @return mixed
     */
    public function update(Requests\Admin\EventUpdate $request)
    {
        $inputs = $request->all();
        $inputs['image'] = $request->file('image');
        Event::edit($request->get('id'), $inputs);
        return $this->redirectResponse('admin/events');
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
        Event::remove($id);

        if (request()->ajax()) {
            return success_json_response('Successfully deleted event.');
        }

        return $this->redirectResponse();
    }
}
