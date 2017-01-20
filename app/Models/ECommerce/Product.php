<?php

namespace App\Models\ECommerce;

use App\Models\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    private static $params;
    public static $low_in_qty = 50;

    protected static $writable_columns = [
        'user_id', 'product_category_id',
        'name', 'description', 'features', 'custom_fields', 'sku',

        'is_featured', 'is_latest', 'is_sale',

        'is_qty_required', 'qty',

        'srp', 'srp_discounted',
        'is_taxable', 'is_discountable',

        'is_disabled', 'is_draft'
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
        $select[] = 'products.*';

        $select[] = DB::raw('product_categories.name as category_name');
        $select[] = DB::raw('users.first_name, users.last_name, CONCAT(users.first_name, " ", users.last_name) as full_name');

        $query = self::select($select);
        $query->join('product_categories', 'products.product_category_id', '=', 'product_categories.id');

        $query->join('users', 'products.user_id', '=', 'users.id');

        if (isset($params['id'])) {
            $query->where('products.id', $params['id']);
        }

        if (isset($params['user_id'])) {
            $query->where('products.user_id', $params['user_id']);
        }

        if (isset($params['low_in_qty'])) {
            $query->where('products.qty', '<=', self::$low_in_qty);
            $query->where('products.is_qty_required', 0);
        }

        if (isset($params['is_disabled'])) {
            $query->where('products.is_disabled', '=', $params['is_disabled']);
        }

        if (isset($params['is_draft'])) {
            $query->where('products.is_draft', '=', $params['is_draft']);
        }

        if (isset($params['is_featured'])) {
            $query->where('products.is_featured', $params['is_featured']);
        }

        if (isset($params['is_qty_required'])) {
            $query->where('products.is_qty_required', $params['is_qty_required']);
        }

        if (isset($params['is_latest'])) {
            $query->where('products.is_latest', $params['is_latest']);
        }

        if (isset($params['is_sale'])) {
            $query->where('products.is_sale', $params['is_sale']);
        }

        if (isset($params['min_srp']) && isset($params['max_srp'])) {
            $query->whereBetween('products.srp', [$params['min_srp'], $params['max_srp']]);
        }

        if (isset($params['product_category_id'])) {
            $has_parent = false;

            // list and get the sub categories
            $product_category = ProductCategory::single($params['product_category_id']);
            if ($product_category) {
                $sub = ProductCategory::get(['all' => true, 'parent_id' => $product_category->id]);

                if (count($sub)) {
                    $has_parent = true;

                    // list of category id
                    $su_list_id = [];
                    foreach ($sub as $row) {
                        $su_list_id[] = $row->id;
                    }

                    array_push($su_list_id, $params['product_category_id']);
                    $query->whereIn('products.product_category_id', $su_list_id);
                }
            }

            if ($has_parent === false) {
                $query->where('products.product_category_id', $params['product_category_id']);
            }
        }

        if (isset($params['search'])) {
            self::$params = $params;
            $query->where(function ($query) {
                $query->where('products.name', 'LIKE', '%' . self::$params['search'] . '%')
                    ->orWhere('products.description', 'LIKE', '%' . self::$params['search'] . '%')
                    ->orWhere('products.features', 'LIKE', '%' . self::$params['search'] . '%')
                    ->orWhere('products.custom_fields', 'LIKE', '%' . self::$params['search'] . '%');
            });
        }

        $query->orderBy('products.created_at', 'DESC');

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

            // images
            $query = self::imagesQuery($query->id, $query);

            // format srp
            $query->srp_format = money($query->srp);

            // format srp old
            $query->srp_discounted_format = money($query->srp_discounted);
        } else {
            foreach ($query as $row) {
                // images
                $row = self::imagesQuery($row->id, $row);

                // format srp
                $row->srp_format = money($row->srp);

                // format srp old
                $row->srp_discounted_format = money($row->srp_discounted);
            }
        }

        return $query;
    }

    /**
     * Product images
     *
     * @param $id
     * @param $row
     * @return mixed
     */
    public static function imagesQuery($id, $row)
    {
        // images
        $row->images = Image::get([
            'all' => true,
            'type' => 'products',
            'source_id' => $id
        ]);

        // cover
        $row->cover = get_image(null);
        if (count($row->images)) {
            $row->cover = $row->images[0]->path;
        }

        return $row;
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
                if ($key != 'images') {
                    $store[$key] = $value;
                }
            }
        }

        // check for values
        $store = self::_checkBoxValues($store);

        $store['created_at'] = sql_date();
        $id = (int)self::insertGetId($store);

        // process multiple images
        self::_processImages($id, $inputs);

        return $id;
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
                if ($key != 'images') {
                    $update[$key] = $value;
                }
            }
        }

        // check for values
        $update = self::_checkBoxValues($update);

        // process multiple images
        self::_processImages($id, $inputs);

        return (bool)$query->update($update);
    }

    /**
     * Check values of checkbox
     *
     * @param array $inputs
     * @return array
     */
    private static function _checkBoxValues($inputs = [])
    {
        foreach (['is_qty_required', 'is_disabled', 'is_featured', 'is_latest', 'is_sale', 'is_draft'] as $item) {
            if (!isset($inputs[$item])) {
                $inputs[$item] = 0;
            }
        }

        return $inputs;
    }

    /**
     * Process images
     *
     * @param $id
     * @param $inputs
     * @return mixed
     */
    private static function _processImages($id, $inputs)
    {
        // multiple images
        $product = self::find($id);

        if ($product) {
            if (isset($inputs['images'])) {
                if (is_array($inputs['images'])) {
                    foreach ($inputs['images'] as $image) {
                        // upload new cover
                        upload_image($image, [
                            'user_id' => $product->user_id,
                            'source_id' => $product->id,
                            'title' => $product->name,
                            'type' => 'products',
                            'crop_auto' => true
                        ]);
                    }
                }
            }
        }

        return $product;
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
        // delete all images product
        $product = self::single($id);
        if ($product) {
            Image::batchRemove($id, 'products');
        }

        return (bool)self::destroy($id);
    }
}
