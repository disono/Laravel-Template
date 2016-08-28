<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Page;
use App\Models\PageCategory;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * List data
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $content['title'] = app_title('Pages');

        $options = [];
        if ($request->get('page_category_id')) {
            $options['page_category_id'] = $request->get('page_category_id');
        }

        if ($request->get('search')) {
            $options['search'] = $request->get('search');
        }

        $content['pages'] = Page::get($options);
        $content['page_categories'] = PageCategory::all();
        $content['request'] = $request;

        return admin_view('page.index', $content);
    }

    /**
     * Create new data
     *
     * @return mixed
     */
    public function create()
    {
        $content['title'] = app_title('Create Page');
        $content['page_categories'] = PageCategory::all();

        return admin_view('page.create', $content);
    }

    /**
     * Store new data
     *
     * @param Requests\Admin\PageStore $request
     * @return mixed
     */
    public function store(Requests\Admin\PageStore $request)
    {
        $inputs = $request->all();
        $inputs['image'] = $request->file('image');
        $inputs['user_id'] = auth()->user()->id;
        Page::store($inputs);

        return redirect('admin/pages');
    }

    /**
     * Edit data
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $content['title'] = app_title('Edit Page');
        $data = Page::single($id);
        if (!$data) {
            abort(404);
        }
        $content['page'] = $data;
        $content['page_categories'] = PageCategory::all();

        return admin_view('page.edit', $content);
    }

    /**
     * Update data
     *
     * @param Requests\Admin\PageUpdate $request
     * @return mixed
     */
    public function update(Requests\Admin\PageUpdate $request)
    {
        $inputs = $request->all();
        $inputs['image'] = $request->file('image');
        Page::edit($request->get('id'), $inputs);

        return redirect('admin/pages');
    }

    /**
     * Delete data
     *
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        Page::remove($id);

        if (request()->ajax()) {
            return success_json_response('Successfully deleted page.');
        }

        return redirect()->back();
    }
}
