<?php

namespace App\Http\Controllers\Admin\ECommerce;

use App\Http\Controllers\Controller;
use App\Models\ECommerce\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    /**
     * List data
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $content['title'] = app_title('Product Category');
        $content['product_categories'] = ProductCategory::get(request_options(request(), [
            'search'
        ]));
        $content['request'] = $request;

        return admin_view('ecommerce.category.index', $content);
    }

    /**
     * Create new data
     *
     * @return mixed
     */
    public function create()
    {
        $content['title'] = app_title('Create Product Category');
        return admin_view('ecommerce.category.create', $content);
    }

    /**
     * Store new data
     *
     * @return mixed
     */
    public function store()
    {
        $request = request();
        if (!$request->get('name')) {
            return redirect()->back()->withErrors([
                'name' => 'Name is required.'
            ]);
        }

        ProductCategory::store($request->all());

        return redirect('admin/product/category');
    }

    /**
     * Edit data
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $content['title'] = app_title('Edit Product Category');
        $data = ProductCategory::single($id);
        if (!$data) {
            abort(404);
        }
        $content['product_category'] = $data;

        return admin_view('ecommerce.category.edit', $content);
    }

    /**
     * Update data
     *
     * @return mixed
     */
    public function update()
    {
        $request = request();
        if (!$request->get('name')) {
            return redirect()->back()->withErrors([
                'name' => 'Name is required.'
            ]);
        }

        ProductCategory::edit($request->get('id'), $request->all());

        return redirect('admin/product/category');
    }

    /**
     * Delete data
     *
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        $allowed = ProductCategory::remove($id);

        if (request()->ajax()) {
            if (!$allowed) {
                return failed_json_response('This resource is not allowed to delete.');
            }

            return success_json_response('Successfully deleted product category.');
        }

        return redirect()->back();
    }
}
