<?php

namespace App\Models\ECommerce;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    private static $params;

    protected static $writable_columns = [
        'parent_id', 'name'
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
        $select[] = 'product_categories.*';
        $query = self::select($select);

        if (isset($params['id'])) {
            $query->where('id', $params['id']);
        }

        if (isset($params['parent_id'])) {
            $query->where('parent_id', $params['parent_id']);
        }

        if (isset($params['exclude'])) {
            $exclude = $params['exclude'];
            foreach ($exclude['val'] as $key => $val) {
                $query->where($exclude['key'], '<>', $val);
            }
        }

        if (isset($params['search'])) {
            self::$params = $params;
            $query->where(function ($query) {
                $query->where('name', 'LIKE', '%' . self::$params['search'] . '%');
            });
        }

        $query->orderBy('created_at', 'DESC');

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
     * Category tree
     *
     * @param array $params
     * @return mixed
     */
    public static function getTree($params = [])
    {
        $params['all'] = true;
        $query = self::get($params);

        $categories = [];
        foreach ($query as $category) {
            $categories[] = [
                'id' => $category->id,
                'name' => $category->name,
                'parent_id' => $category->parent_id
            ];
        }

        $map = array(
            0 => array('sub_categories' => array())
        );

        foreach ($categories as &$category) {
            $category['sub_categories'] = array();
            $map[$category['id']] = &$category;
        }

        foreach ($categories as &$category) {
            $map[$category['parent_id']]['sub_categories'][] = &$category;
        }

        return $map[0]['sub_categories'];
    }

    /**
     * List categories
     *
     * @param array $array
     * @param int $count
     * @param bool $include_tab
     * @param bool $strong
     * @return mixed
     */
    public static $list = [];

    public static function print_ar($array = [], $count = 0, $include_tab = true, $strong = false)
    {
        $i = 0;
        $tab = '';
        while ($i != $count) {
            $i++;
            $tab .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        }

        foreach ($array as $key) {
            $tab_primary = substr($tab, 0, -12);

            if ($strong) {
                if ($include_tab) {
                    $key['name'] = $tab_primary . (($tab === '') ? '<strong>' . $key['name'] . '</strong>' : $key['name']);
                } else {
                    $key['name'] = (($tab === '') ? '<strong>' . $key['name'] . '</strong>' : $key['name']);
                }
            } else {
                if ($include_tab) {
                    $key['name'] = $tab_primary . (($tab === '') ? $key['name'] : $key['name']);
                } else {
                    $key['name'] = (($tab === '') ? $key['name'] : $key['name']);
                }
            }

            $key['tab'] = $tab;
            self::$list[] = (object)$key;

            if (count($key['sub_categories']) > 0) {
                $count++;
                self::print_ar($key['sub_categories'], $count, $include_tab);
                $count--;
            }
        }

        return self::$list;
    }

    /**
     * Formatted tab list
     *
     * @param array $params
     * @return mixed
     */
    public static function nestedToTabs($params = [])
    {
        $include_tab = true;
        if (isset($params['include_tab'])) {
            $include_tab = $params['include_tab'];
        }

        $strong = false;
        if (isset($params['strong'])) {
            $strong = $params['strong'];
        }

        $data = self::print_ar(self::getTree($params), 0, $include_tab, $strong);

        // query sub categories (counting)
        $query = [
            'all' => true
        ];

        if (isset($params['exclude'])) {
            $query = array_merge($query, $params['exclude']);
        }
        $count_cat = count(self::get($query));

        $query = [];
        $num = 0;
        foreach ($data as $row) {
            if ($num < $count_cat) {
                $query[] = $row;
            }

            $num++;
        }

        return $query;
    }

    /**
     * Nested to ul
     *
     * @param $data
     * @return string
     */
    public
    static function nested2ul($data)
    {
        $result = array();

        if (sizeof($data) > 0) {
            $result[] = '<ul style="list-style-type: none;">';

            foreach ($data as $row) {
                $result[] = sprintf(
                    '<li><a href="' . url('search?category=' . $row['slug']) . '">%s</a> %s</li>',
                    $row['name'],
                    self::nested2ul($row['sub_categories'])
                );
            }

            $result[] = '</ul>';
        }

        return implode($result);
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
        } else {
            foreach ($query as $row) {

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

        // make sure parent id is not self
        if ($id == $inputs['parent_id']) {
            return false;
        }

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
