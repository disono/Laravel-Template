<?php

namespace App\Models\ECommerce;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderedItem extends Model
{
    private static $params;

    protected static $writable_columns = [
        'order_id', 'product_id',
        'qty', 'srp',
        'discount', 'tax', 'shipping', 'total',
        'status',
    ];

    public function __construct(array $attributes = [])
    {
        $this->fillable(self::$writable_columns);
        parent::__construct($attributes);
    }

    /**
     * Get single data
     *
     * @param $id
     * @param string $column
     * @return null
     */
    public static function single($id, $column = 'id')
    {
        if (!$id) {
            return null;
        }

        return self::get([
            'single' => true,
            $column => $id
        ]);
    }

    /**
     * Get data
     *
     * @param array $params
     * @return null
     */
    public static function get($params = [])
    {
        $select[] = 'ordered_items.*';

        $select[] = DB::raw('products.name as product_name, products.description as product_description');

        $query = self::select($select)
            ->join('products', 'ordered_items.product_id', '=', 'products.id');

        if (isset($params['id'])) {
            $query->where('ordered_items.id', $params['id']);
        }

        if (isset($params['order_id'])) {
            $query->where('ordered_items.order_id', $params['order_id']);
        }

        if (isset($params['product_id'])) {
            $query->where('ordered_items.product_id', $params['product_id']);
        }

        if (isset($params['status'])) {
            $query->where('ordered_items.status', $params['status']);
        }

        if (isset($params['exclude'])) {
            $exclude = $params['exclude'];
            foreach ($exclude['val'] as $key => $val) {
                $query->where('ordered_items.' . $exclude['key'], '<>', $val);
            }
        }

        if (isset($params['search'])) {
            self::$params = $params;
            $query->where(function ($query) {
                $query->where('ordered_items.email', 'LIKE', '%' . self::$params['search'] . '%')
                    ->orWhere('ordered_items.phone', 'LIKE', '%' . self::$params['search'] . '%')
                    ->orWhere('ordered_items.full_name', 'LIKE', '%' . self::$params['search'] . '%')
                    ->orWhere('ordered_items.billing_address', 'LIKE', '%' . self::$params['search'] . '%')
                    ->orWhere('ordered_items.shipping_address', 'LIKE', '%' . self::$params['search'] . '%');
            });
        }

        $query->orderBy('ordered_items.created_at', 'DESC');

        if (isset($params['object'])) {
            return $query;
        } else {
            if (isset($params['single'])) {
                return self::_format($query->first(), $params);
            } else if (isset($params['all'])) {
                return self::_format($query->get(), $params);
            } else {
                $query = paginate($query);

                return self::_format($query, $params);
            }
        }
    }

    /**
     * Add formatting on data
     *
     * @param $query
     * @param array $params
     * @return null
     */
    private static function _format($query, $params = [])
    {
        if (isset($params['single'])) {
            if (!$query) {
                return null;
            }

            $query->formatted_discount = money($query->discount);
            $query->formatted_shipping = money($query->shipping);
            $query->formatted_tax = money($query->tax);
            $query->formatted_total = money($query->total);

            // images
            $query = Product::imagesQuery($query->product_id, $query);
        } else {
            foreach ($query as $row) {
                // images
                $row = Product::imagesQuery($row->product_id, $row);

                $row->formatted_discount = money($row->discount);
                $row->formatted_shipping = money($row->shipping);
                $row->formatted_tax = money($row->tax);
                $row->formatted_total = money($row->total);
            }
        }

        return $query;
    }

    /**
     * Store new data
     *
     * @param array $inputs
     * @return bool
     */
    public static function store($inputs = [])
    {
        $store = [];

        foreach ($inputs as $key => $value) {
            if (in_array($key, self::$writable_columns)) {
                $store[$key] = $value;
            }
        }

        $store['created_at'] = sql_date();
        return (int)self::insertGetId($store);
    }

    /**
     * Ordered item status
     *
     * @param $id
     * @param $status
     * @return bool
     */
    public static function status($id, $status)
    {
        $ordered_item = self::single($id);

        if (!$ordered_item) {
            return false;
        }

        $product = Product::single($ordered_item->product_id);
        if (!$product) {
            return false;
        }

        // update the products quantity (inventory)
        self::_processProductQuantity($product, $ordered_item, $status);

        // update the status
        self::edit($id, [
            'status' => $status
        ]);

        return true;
    }

    /**
     * Update product quantity
     *
     * @param $product
     * @param $ordered_item
     * @param $status
     */
    private static function _processProductQuantity($product, $ordered_item, $status)
    {
        // check if product requires quantity
        if (!$product->is_qty_required) {
            return;
        }

        if ($ordered_item->status != $status && $product) {
            if ($status == 'Completed' || $status == 'Shipped' || $status == 'Processing' && ($ordered_item->status == 'Pending')) {
                // update the product quantity (deduct)
                $qty = (int)$product->qty - (int)$ordered_item->qty;
                Product::edit($ordered_item->product_id, [
                    'qty' => $qty
                ]);
            } else if ($status == 'Declined' || $status == 'Cancelled' && ($ordered_item->status == 'Completed' ||
                    $ordered_item->status == 'Processing' || $ordered_item->status == 'Shipped')
            ) {
                // update the product quantity (add)
                $qty = (int)$product->qty + (int)$ordered_item->qty;
                Product::edit($ordered_item->product_id, [
                    'qty' => $qty
                ]);
            }
        }
    }

    /**
     * Delete data
     *
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public static function remove($id)
    {
        return (bool)self::destroy($id);
    }

    /**
     * Update data
     *
     * @param $id
     * @param array $inputs
     * @param null $column_name
     * @return bool
     */
    public static function edit($id, $inputs = [], $column_name = null)
    {
        $update = [];
        $query = null;

        if (!$column_name) {
            $column_name = 'id';
        }

        if ($id && !is_array($column_name)) {
            $query = self::where($column_name, $id);
        } else {
            $i = 0;
            foreach ($column_name as $key => $value) {
                if (!in_array($key, self::$writable_columns)) {
                    return false;
                }

                if (!$i) {
                    $query = self::where($key, $value);
                } else {
                    if ($query) {
                        $query->where($key, $value);
                    }
                }
                $i++;
            }
        }

        foreach ($inputs as $key => $value) {
            if (in_array($key, self::$writable_columns)) {
                $update[$key] = $value;
            }
        }

        return (bool)$query->update($update);
    }
}
