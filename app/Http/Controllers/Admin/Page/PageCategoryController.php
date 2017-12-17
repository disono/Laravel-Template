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
use App\Models\PageCategory;
use Illuminate\Http\Request;

class PageCategoryController extends Controller
{
    public function __construct()
    {
        $this->view = 'page-category.';
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
        $this->title = 'Page Categories';
        $this->content['page_categories'] = PageCategory::fetch();
        return $this->response('index');
    }

    /**
     * Create new data
     *
     * @return mixed
     */
    public function create()
    {
        $this->title = 'Create PageCategory';
        return $this->response('create');
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
        return $this->redirectResponse('admin/page-categories');
    }

    /**
     * Edit data
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $data = PageCategory::single($id);
        if (!$data) {
            abort(404);
        }

        $this->title = 'Edit Page Category';
        $this->content['page_category'] = $data;
        return $this->response('edit');
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
        return $this->redirectResponse('admin/page-categories');
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
        PageCategory::remove($id);

        if (request()->ajax()) {
            return success_json_response('Successfully deleted page category.');
        }

        return redirect()->back();
    }
}
