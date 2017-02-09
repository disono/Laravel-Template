<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */
namespace App\Http\Controllers\Admin\Page;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\PageCategory;
use Illuminate\Http\Request;

class PageCategoryController extends Controller
{
    /**
     * List data
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $content['title'] = app_title('Page Categories');
        $content['page_categories'] = PageCategory::get();
        $content['request'] = $request;

        return admin_view('page-category.index', $content);
    }

    /**
     * Create new data
     *
     * @return mixed
     */
    public function create()
    {
        $content['title'] = app_title('Create PageCategory');
        return admin_view('page-category.create', $content);
    }

    /**
     * Store new data
     *
     * @param Requests\Admin\PageCategoryStore $request
     * @return mixed
     */
    public function store(Requests\Admin\PageCategoryStore $request)
    {
        PageCategory::store($request->all());

        return redirect('admin/page-categories');
    }

    /**
     * Edit data
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $content['title'] = app_title('Edit Page Category');
        $data = PageCategory::single($id);
        if (!$data) {
            abort(404);
        }
        $content['page_category'] = $data;

        return admin_view('page-category.edit', $content);
    }

    /**
     * Update data
     *
     * @param Requests\Admin\PageCategoryUpdate $request
     * @return mixed
     */
    public function update(Requests\Admin\PageCategoryUpdate $request)
    {
        PageCategory::edit($request->get('id'), $request->all());

        return redirect('admin/page-categories');
    }

    /**
     * Delete data
     *
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        PageCategory::remove($id);

        if (request()->ajax()) {
            return success_json_response('Successfully deleted page category.');
        }

        return redirect()->back();
    }
}
