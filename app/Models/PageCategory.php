<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models;

use Illuminate\Support\Facades\DB;

class PageCategory extends AppModel
{
    protected static $table_name = 'page_categories';
    protected static $writable_columns = [
        'parent_id', 'name', 'description',
        'is_link', 'external_link'
    ];

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

        return self::fetch([
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
    public static function fetch($params = [])
    {
        $table_name = (new self)->getTable();
        $select[] = $table_name . '.*';

        $select[] = DB::raw('slugs.name as slug');

        $query = self::select($select)
            ->join('slugs', function ($join) use ($table_name) {
                $join->on($table_name . '.id', '=', 'slugs.source_id')
                    ->where('slugs.source_type', '=', 'page_category');
            });

        if (isset($params['slug'])) {
            $query->where('slugs.name', $params['slug']);
        }

        // where equal
        $query = self::_whereEqual($query, $params, self::$writable_columns, $table_name);

        // exclude and include
        $query = self::_excInc($query, $params, self::$writable_columns, $table_name);

        // search
        $query = self::_search($query, $params, self::$writable_columns, $table_name, [
            'slugs.name'
        ]);

        $query->orderBy($table_name . '.name', 'DESC');

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
     * Get all data no pagination
     *
     * @param array $params
     * @return null
     */
    public static function getAll($params = [])
    {
        $params['all'] = true;
        return self::fetch($params);
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
        $query = self::fetch($params);

        $categories = [];
        foreach ($query as $category) {
            $categories[] = [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
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
     * Add formatting
     *
     * @param array $array
     * @param int $count
     * @param bool $include_tab
     * @param bool $strong
     * @return array
     */
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
        $count_cat = count(self::fetch($query));

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
    public static function nested2ul($data)
    {
        $result = array();

        if (sizeof($data) > 0) {
            $result[] = '<ul style="list-style-type: none;">';

            foreach ($data as $row) {
                $result[] = sprintf(
                    '<li><a href="' . url('pages?category_slug=' . $row['slug']) . '">%s</a> %s</li>',
                    $row['name'],
                    self::nested2ul($row['sub_categories'])
                );
            }

            $result[] = '</ul>';
        }

        return implode($result);
    }

    /**
     * Category menu
     *
     * @param $include
     * @return string
     */
    public static function categoryMenu($include)
    {
        $view = '';
        foreach ($include as $id) {
            $top_menu = self::single($id);

            if ($top_menu) {

                if ($top_menu->is_link) {
                    $view .= '<li class="nav-item"><a class="nav-link" href="' . (($top_menu->external_link) ? $top_menu->external_link :
                            url('pages?category_slug=' . $top_menu->slug)) . '">' . $top_menu->name . '</a></li>';
                } else {
                    $view .= '<li class="nav-item dropdown">';

                    // top menu
                    $view .= '<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' .
                        $top_menu->name . '</a>';

                    // sub-menu
                    $view .= self::subMenu($top_menu->id);
                    $view .= '</li>';
                }
            }
        }
        return $view;
    }

    /**
     * Get the sub-menu for top category indicated (id)
     *
     * @param $id
     *  The top menu
     * @return null
     */
    public static function subMenu($id)
    {
        $top_menu = self::single($id);
        $view = '';

        if ($top_menu) {
            // pages
            $pages_top = Page::fetch([
                'page_category_id' => $top_menu->id,
                'all' => true
            ]);
            foreach ($pages_top as $page) {
                $view .= '<li><a class="dropdown-item" href="' . $page->url . '">' . $page->name . '</a></li>';
            }

            // sub-menu (page category)
            $subs = self::fetch([
                'parent_id' => $top_menu->id,
                'all' => true
            ]);
            foreach ($subs as $cat) {
                $view .= '<li class="dropdown-submenu">';
                $view .= '<ul class="dropdown-menu">';

                if ($cat->is_link) {
                    $view .= '<li><a class="dropdown-item" href="' . (($cat->external_link) ? $cat->external_link :
                            url('pages?category_slug=' . $cat->slug)) . '">' . $cat->name . '</a></li>';
                } else {
                    $view .= '<li class="dropdown-submenu">';
                    $view .= '<a class="dropdown-item dropdown-toggle" href="#">' . $cat->name . ' <span class="caret-right"></span></a>';
                    $view .= self::subMenu($cat->id);
                    $view .= '</li>';
                }

                $view .= '</ul>';
                $view .= '</li>';
            }
        }

        return ($view != '') ? '<ul class="dropdown-menu">' . $view . '</ul>' : '';
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
                if ($key != 'slug') {
                    $store[$key] = $value;
                }
            }
        }

        if (!isset($store['parent_id'])) {
            $store['parent_id'] = 0;
        }

        if (!isset($store['is_link'])) {
            $store['is_link'] = 0;
        }

        $store['created_at'] = sql_date();
        $id = (int)self::insertGetId($store);

        // insert slug
        if ($id && isset($inputs['slug'])) {
            $slug = Slug::store([
                'source_id' => $id,
                'source_type' => 'page_category',
                'name' => $inputs['slug']
            ]);

            // revert
            if (!$slug) {
                self::remove($id);
            }
        }

        return $id;
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
        if (isset($inputs['parent_id'])) {
            if ($id == $inputs['parent_id']) {
                return false;
            }
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
                if ($key != 'slug') {
                    $update[$key] = $value;
                }
            }
        }

        // update slug
        if ($id && isset($inputs['slug'])) {
            if ($inputs['slug']) {
                $slug = Slug::fetch([
                    'source_id' => $id,
                    'source_type' => 'page_category',
                    'single' => true
                ]);

                if ($slug) {
                    Slug::edit($slug->id, [
                        'name' => $inputs['slug']
                    ]);
                }
            }
        }

        if (!isset($update['parent_id'])) {
            $update['parent_id'] = 0;
        }

        if (!isset($update['is_link'])) {
            $update['is_link'] = 0;
        }

        // store to activity logs
        ActivityLog::log($id, self::$writable_columns, $query->first(), $inputs, (new self)->getTable());

        return (bool)$query->update($update);
    }
}
