<?php

namespace App\Http\Controllers\Admin\ECommerce;

use App\Http\Controllers\Controller;
use App\Models\ECommerce\PaymentType;
use Illuminate\Http\Request;

class PaymentTypeController extends Controller
{
    /**
     * List data
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $content['title'] = app_title('Payment Type');
        $content['payment_type'] = PaymentType::get(request_options(request(), [
            'search'
        ]));
        $content['request'] = $request;

        return admin_view('ecommerce.payment_type.index', $content);
    }

    /**
     * Create new data
     *
     * @return mixed
     */
    public function create()
    {
        $content['title'] = app_title('Create Payment Type');
        return admin_view('ecommerce.payment_type.create', $content);
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

        PaymentType::store($request->all());

        return redirect('admin/ecommerce/payment-type');
    }

    /**
     * Edit data
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $content['title'] = app_title('Edit Payment Type');
        $data = PaymentType::single($id);
        if (!$data) {
            abort(404);
        }
        $content['payment_type'] = $data;

        return admin_view('ecommerce.payment_type.edit', $content);
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

        PaymentType::edit($request->get('id'), $request->all());

        return redirect('admin/ecommerce/payment-type');
    }

    /**
     * Delete data
     *
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        $allowed = PaymentType::remove($id);

        if (request()->ajax()) {
            if (!$allowed) {
                return failed_json_response('This resource is not allowed to delete.');
            }

            return success_json_response('Successfully deleted payment type.');
        }

        return redirect()->back();
    }
}
