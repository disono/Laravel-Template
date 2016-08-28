<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */
namespace App\Http\Controllers\Admin;

use App\Models\Event;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * List data
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $content['title'] = app_title('Events');

        $options = [];
        if ($request->get('search')) {
            $options['search'] = $request->get('search');
        }

        $content['events'] = Event::get($options);
        $content['request'] = $request;
        return admin_view('event.index', $content);
    }

    /**
     * Create new data
     *
     * @return mixed
     */
    public function create()
    {
        $content['title'] = app_title('Create Event');

        return admin_view('event.create', $content);
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

        return redirect('admin/events');
    }

    /**
     * Edit data
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $content['title'] = app_title('Edit Event');
        $data = Event::single($id);
        if (!$data) {
            abort(404);
        }
        $content['event'] = $data;

        return admin_view('event.edit', $content);
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

        return redirect('admin/events');
    }

    /**
     * Delete data
     *
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        Event::remove($id);

        if (request()->ajax()) {
            return success_json_response('Successfully deleted event.');
        }

        return redirect()->back();
    }
}
