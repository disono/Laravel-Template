<?php

namespace App\Http\Controllers\Admin\ECommerce;

use App\Http\Controllers\Controller;
use App\Models\ECommerce\Order;
use App\Models\ECommerce\OrderedItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * List of orders
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $content['title'] = app_title('Orders');
        $content['orders'] = Order::get(request_options(request(), [
            'search'
        ]));
        $content['request'] = $request;

        return admin_view('ecommerce.order.index', $content);
    }

    /**
     * Show ordered items
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $content['title'] = app_title('Ordered Items');
        $content['ordered_items'] = OrderedItem::get(request_options(request(), [
            'search'
        ], [
            'order_id' => $id
        ]));
        $content['request'] = request();

        return admin_view('ecommerce.order.show', $content);
    }

    /**
     * Ordered Item status
     *
     * @param $id
     * @param $status
     * @return \Illuminate\Http\RedirectResponse
     */
    public function status($id, $status)
    {
        OrderedItem::status($id, $status);

        return redirect()->back();
    }
}
