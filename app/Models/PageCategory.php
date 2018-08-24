<?php
/**
 * @author          Archie, Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (webmons.com), 2016-2018
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models;

use App\Models\Vendor\BaseModel;

class PageCategory extends BaseModel
{
    protected static $tableName = 'page_categories';
    protected static $writableColumns = [
        'parent_id', 'name', 'description', 'slug'
    ];

    protected static $inputIntegers = ['parent_id'];

    private static $list = [];

    public function __construct(array $attributes = [])
    {
        $this->fillable(self::$writableColumns);
        parent::__construct($attributes);
    }

    /**
     * Custom method for editing
     *
     * @param $tableName
     * @param $query
     * @param $inputs
     * @return bool
     */
    public static function actionEditBefore($tableName, $query, $inputs)
    {
        if (!$query) {
            return false;
        }

        // do not make parent id to self
        if (isset($inputs['parent_id'])) {
            if ($query->id == $inputs['parent_id']) {
                return false;
            }
        }

        return true;
    }

    /**
     * Custom action remove
     *
     * @param $query
     * @return bool
     */
    public static function actionRemove($query)
    {
        foreach ($query as $row) {
            Page::where('page_category_id', $row->id)->delete();

            // delete the pages using the parent id
            foreach (PageCategory::where('parent_id', $row->id)->get() as $sub) {
                Page::where('page_category_id', $sub->id)->delete();
            }
            PageCategory::where('parent_id', $row->id)->delete();
        }

        return true;
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
                    '<li><a href="' . url('p/category/' . $row['slug']) . '">%s</a> %s</li>',
                    $row['name'],
                    self::nested2ul($row['sub_categories'])
                );
            }
            $result[] = '</ul>';
        }

        return implode($result);
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

        // query sub categories (counting)
        $data = self::print_ar(self::fetchTree($params), 0, $include_tab, $strong);
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
     * Add formatting
     *
     * @param array $array
     * @param int $count
     * @param bool $include_tab
     * @param bool $strong
     * @param string $_tab
     * @return array
     */
    public static function print_ar($array = [], $count = 0, $include_tab = true, $strong = false, $_tab = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;")
    {
        $i = 0;
        $tab = '';
        while ($i != $count) {
            $i++;
            $tab .= $_tab;
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
     * Category tree
     *
     * @param array $params
     * @return mixed
     */
    public static function fetchTree($params = [])
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

    public function page()
    {
        return $this->hasMany('App\Models\Page');
    }
}
