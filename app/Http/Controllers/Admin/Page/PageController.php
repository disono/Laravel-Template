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
use App\Models\Page;
use App\Models\PageCategory;

class PageController extends Controller
{
    public function __construct()
    {
        $this->view = 'page.';
        $this->view_type = 'admin';
        parent::__construct();
    }

    /**
     * List data
     *
     * @return mixed
     */
    public function index()
    {
        $this->title = 'Pages';
        $this->content['pages'] = Page::fetch(request_options('page_category_id|search'));
        $this->content['page_categories'] = PageCategory::all();
        return $this->response('index');
    }

    /**
     * Create new data
     *
     * @return mixed
     */
    public function create()
    {
        $this->title = 'Create Page';
        $this->content['page_categories'] = PageCategory::all();
        return $this->response('create');
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
        return $this->redirectResponse('admin/page/edit/' . Page::store($inputs));
    }

    /**
     * Edit data
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $data = Page::single($id);
        if (!$data) {
            abort(404);
        }

        $this->title = 'Edit Page';
        $this->content['page'] = $data;
        $this->content['page_categories'] = PageCategory::all();
        return $this->response('edit');
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
        return $this->redirectResponse('admin/pages');
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
        Page::remove($id);

        if (request()->ajax()) {
            return success_json_response('Successfully deleted page.');
        }

        return $this->redirectResponse();
    }
}
