<?php

namespace App\Http\Controllers\Admin;

use App\PageCategory;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

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
    public function ajaxDestroy($id)
    {
        PageCategory::remove($id);

        return success_json_response('Successfully deleted page category.');
    }
}
