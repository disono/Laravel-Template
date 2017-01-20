<?php

namespace App\Http\Controllers\Admin\ECommerce;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ECommerce\ProductStore;
use App\Http\Requests\Admin\ECommerce\ProductUpdate;
use App\Models\ECommerce\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * List data
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $content['title'] = app_title('Products');

        $options = [];
        if (me()->role != 'admin') {
            $options['user_id'] = me()->id;
        }

        $content['products'] = Product::get(request_options($request, [
            'search', 'low_in_qty', 'product_category_id'
        ], $options));
        $content['request'] = $request;

        return admin_view('ecommerce.product.index', $content);
    }

    /**
     * Create new data
     *
     * @return mixed
     */
    public function create()
    {
        $content['title'] = app_title('Create Product');
        return admin_view('ecommerce.product.create', $content);
    }

    /**
     * Store new data
     *
     * @param ProductStore $request
     * @return mixed
     */
    public function store(ProductStore $request)
    {
        $inputs = $request->all();
        $inputs['user_id'] = me()->id;
        $inputs['images'] = $request->file('images');
        Product::store($inputs);

        return redirect('admin/product');
    }

    /**
     * Edit data
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $content['title'] = app_title('Edit Product');
        $data = Product::single($id);
        if (!$data) {
            abort(404);
        }
        $content['product'] = $data;

        return admin_view('ecommerce.product.edit', $content);
    }

    /**
     * Update data
     *
     * @param ProductUpdate $request
     * @return mixed
     */
    public function update(ProductUpdate $request)
    {
        $inputs = $request->all();
        $inputs['images'] = $request->file('images');

        Product::edit($request->get('id'), $inputs);

        return redirect('admin/product');
    }

    /**
     * Delete data
     *
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        $allowed = Product::remove($id);

        if (request()->ajax()) {
            if (!$allowed) {
                return failed_json_response('This resource is not allowed to delete.');
            }

            return success_json_response('Successfully deleted product.');
        }

        return redirect()->back();
    }
}
