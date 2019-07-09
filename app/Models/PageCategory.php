<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models;

use App\Models\Vendor\BaseModel;
use App\Models\Vendor\Facades\File;
use App\Models\Vendor\Facades\Page;

class PageCategory extends BaseModel
{
    protected $tableName = 'page_categories';
    protected $writableColumns = [
        'position', 'parent_id', 'name', 'description', 'slug',
        'is_enabled'
    ];

    protected $inputIntegers = ['parent_id', 'position'];
    protected $columnHasRelations = ['parent_id'];
    protected $inputBooleans = ['is_enabled'];

    protected $files = ['img_active', 'img_inactive', 'img_banner'];
    protected $fileOptions = [
        'img_active' => ['tag' => 'active', 'width' => 64, 'height' => 64, 'remove_previous' => true],
        'img_inactive' => ['tag' => 'inactive', 'width' => 64, 'height' => 64, 'remove_previous' => true],
        'img_banner' => ['tag' => 'banner', 'width' => 640, 'height' => 360, 'remove_previous' => true]
    ];

    private $_categoryList = [];
    private $_categoryParentList = [];

    public function __construct(array $attributes = [])
    {
        $this->fillable($this->writableColumns);
        parent::__construct($attributes);
    }

    public function actionEditBefore($tableName, $query, $inputs)
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

    public function actionRemoveBefore($results)
    {
        foreach ($results as $row) {
            Page::remove($row->id, 'page_category_id');
            $this->remove(['parent_id' => $row->id]);
        }

        return true;
    }

    public function parents($parent_id)
    {
        if (!$parent_id) {
            return [];
        }

        $category = self::where('id', $parent_id)->first();
        if ($category) {
            $category->is_active = count($this->_categoryParentList) ? 0 : 1;
            $this->_categoryParentList[] = $category;
            $this->parents($category->parent_id);
        }

        return array_reverse($this->_categoryParentList);
    }

    /**
     * Categories with sub
     *
     * @param array $params
     * @return array
     */
    public function categories($params = [])
    {
        $categories = [];

        $mainParams = $params;
        $mainParams['parent_id'] = 0;
        foreach ($this->fetchAll($mainParams) as $category) {
            $subParams = $params;
            $subParams['parent_id'] = $category->id;
            $category->sub = $this->subCategories($subParams);
            $categories[] = $category;
        }

        return $categories;
    }

    /**
     * Subcategories
     *
     * @param $params
     * @return array
     */
    public function subCategories($params)
    {
        $categories = [];

        foreach ($this->fetchAll($params) as $category) {
            $subParams = $params;
            $subParams['parent_id'] = $category->id;
            $category->sub = $this->subCategories($subParams);
            $categories[] = $category;
        }

        return $categories;
    }

    /**
     * Formatted tab list
     *
     * @param array $params
     * @return mixed
     */
    public function formattedCategories($params = [])
    {
        // query sub categories (counting)
        return $this->formattingTree($params, $this->fetchTree($params), 0);
    }

    /**
     * Add formatting
     *
     * @param array $params
     * @param array $data
     * @param int $count
     *
     * @return array
     */
    public function formattingTree($params = [], $data = [], $count = 0)
    {
        $addTab = '';
        $tab_in_name = $params['tab_in_name'] ?? false;
        $tab_only = $params['tab_only'] ?? false;
        $tab_numeric = $params['tab_numeric'] ?? 0;
        $tab = $params['tab'] ?? "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

        if ($tab_numeric) {
            $addTab = 0;
        }

        $i = 0;
        while ($i != $count) {
            $i++;

            if ($tab_numeric) {
                $addTab += $tab_numeric;
            } else {
                $addTab .= $tab;
            }
        }

        foreach ($data as $row) {
            // add tab or spacing
            $row['name'] = !$tab_in_name ? $row['name'] : $addTab . $row['name'];
            $row['tab'] = $tab_only || $tab_numeric ? $addTab : NULL;

            $this->_categoryList[] = (object)$row;
            if (count($row['sub_categories']) > 0) {
                $count++;
                $this->formattingTree($params, $row['sub_categories'], $count);
                $count--;
            }
        }

        return $this->_categoryList;
    }

    /**
     * Category tree
     *
     * @param array $params
     *
     * @return mixed
     */
    public function fetchTree($params = [])
    {
        $categories = [];
        $map = [0 => ['sub_categories' => []]];
        $query = $this->fetchAll($params);

        foreach ($query as $category) {
            $categories[] = [
                'id' => $category->id,
                'position' => $category->position,
                'parent_id' => $category->parent_id,
                'name' => $category->name,
                'description' => $category->description,
                'is_enabled' => $category->is_enabled,
                'count_sub' => $category->count_sub
            ];
        }

        foreach ($categories as &$category) {
            $category['sub_categories'] = [];
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

    protected function customQuerySelectList(): array
    {
        return [
            'count_sub' => 'SELECT COUNT(*) FROM page_categories AS sub WHERE sub.parent_id = page_categories.id'
        ];
    }

    protected function dataFormatting($row)
    {
        $this->addDateFormatting($row);

        $row->img_active = File::lookForFile($row->id, 'page_categories', 'active');
        $row->img_inactive = File::lookForFile($row->id, 'page_categories', 'inactive');
        $row->img_banner = File::lookForFile($row->id, 'page_categories', 'banner');

        return $row;
    }
}
