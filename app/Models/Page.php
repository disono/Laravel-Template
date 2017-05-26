<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Page extends AppModel
{
    protected static $writable_columns = [
        'page_category_id',
        'user_id', 'name',
        'content', 'template', 'draft',
        'is_email_to_subscriber'
    ];

    public function __construct(array $attributes = [])
    {
        $this->fillable(self::$writable_columns);
        parent::__construct($attributes);
    }

    /**
     * Get data
     *
     * @param array $params
     * @return null
     */
    public static function get($params = [])
    {
        $table_name = (new self)->getTable();
        $select[] = $table_name . '.*';

        $select[] = DB::raw('page_categories.name as category_name, page_categories.description as category_description, (' . self::_pageCategorySlug() . ') as category_slug');

        $slug = DB::raw('(' . self::_pageSlug() . ')');
        $select[] = DB::raw($slug . ' as slug');

        // month name
        $select[] = DB::raw('(DATE_FORMAT(' . $table_name . '.created_at, "%M")) AS month_name');
        // day name (01, 02, 10 etc.)
        $select[] = DB::raw('(DATE_FORMAT(' . $table_name . '.created_at, "%e")) AS day_name');
        // year name four digits 2017
        $select[] = DB::raw('(DATE_FORMAT(' . $table_name . '.created_at, "%Y")) AS year_name');

        $query = self::select($select)
            ->join('page_categories', $table_name . '.page_category_id', '=', 'page_categories.id');

        if (isset($params['category_slug'])) {
            $query->where(DB::raw('(' . self::_pageCategorySlug() . ')'), $params['category_slug']);
        } else if (isset($params['category_slug_inc'])) {
            $cat_slugs = $params['category_slug_inc'];
            if (!is_array($cat_slugs) && is_string($cat_slugs)) {
                $cat_slugs = explode('|', $cat_slugs);
            }

            $query->whereIn(DB::raw('(' . self::_pageCategorySlug() . ')'), $cat_slugs);
        }

        if (isset($params['slug'])) {
            $query->where($slug, '=', $params['slug']);
        }

        // where equal
        $query = self::_whereEqual($query, $params, self::$writable_columns, $table_name);

        // exclude and include
        $query = self::_excInc($query, $params, self::$writable_columns, $table_name);

        // search
        $query = self::_search($query, $params, self::$writable_columns, $table_name, [
            $slug
        ]);

        // search year
        // 2017
        if (isset($params['search_year'])) {
            $query->where(DB::raw('DATE_FORMAT(' . $table_name . '.created_at, "%Y")'), $params['search_year']);
        }

        // search month
        // January
        if (isset($params['search_month'])) {
            $query->where(DB::raw('DATE_FORMAT(' . $table_name . '.created_at, "%M")'), $params['search_month']);
        }

        // search day
        // 01
        if (isset($params['search_day'])) {
            $query->where(DB::raw('DATE_FORMAT(' . $table_name . '.created_at, "%e")'), $params['search_day']);
        }

        if (isset($params['order_by_month'])) {
            // order by month and group by
            $query->groupBy(DB::raw('DATE_FORMAT(' . $table_name . '.created_at, "%M")'));
            $query->orderBy(DB::raw('DATE_FORMAT(' . $table_name . '.created_at, "%m")'), 'DESC');
        } else if (isset($params['order_by_day'])) {
            // order by day and group by
            $query->groupBy(DB::raw('DATE_FORMAT(' . $table_name . '.created_at, "%e")'));
            $query->orderBy(DB::raw('DATE_FORMAT(' . $table_name . '.created_at, "%e")'), 'DESC');
        } else if (isset($params['order_by_year'])) {
            // order by year and group by
            $query->groupBy(DB::raw('DATE_FORMAT(' . $table_name . '.created_at, "%Y")'));
            $query->orderBy(DB::raw('DATE_FORMAT(' . $table_name . '.created_at, "%Y")'), 'DESC');
        } else {
            $query->orderBy($table_name . '.created_at', 'DESC');
        }

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
        return self::get($params);
    }

    /**
     * Top pages (latest)
     *
     * @param string $category
     * @param int $limit
     * @return null
     * @internal param array $params
     */
    public static function latest($category = null, $limit = 5)
    {
        $params['object'] = true;
        if ($category) {
            $params['category_slug_inc'] = $category;
        }

        return self::_format(self::get($params)->limit($limit)->get());
    }

    /**
     * Archive list
     *
     * @param array $params
     * @return array
     */
    public static function archive($params = [])
    {
        $years = self::get(array_merge([
            'order_by_year' => true,
            'all' => true
        ], $params));

        $ar = [];
        foreach ($years as $yr) {
            $ar[] = [
                'top' => $yr,
                'sub' => self::get([
                    'search_year' => $yr->year_name,
                    'order_by_month' => true,
                    'all' => true
                ])
            ];
        }

        return $ar;
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
     * Page category slug
     *
     * @return string
     */
    private static function _pageCategorySlug()
    {
        return 'SELECT name FROM slugs WHERE slugs.source_id = page_categories.id AND slugs.source_type = "page_category"';
    }

    /**
     * Page slug
     *
     * @return string
     */
    private static function _pageSlug()
    {
        return 'SELECT name FROM slugs WHERE slugs.source_id = pages.id AND slugs.source_type = "page"';
    }

    /**
     * Add formatting to data
     *
     * @param $row
     * @return mixed
     */
    public static function _dataFormatting($row)
    {
        // images
        $row->images = Image::getAll([
            'type' => 'page',
            'source_id' => $row->id
        ]);

        // default image
        $row->cover = get_image((count($row->images)) ? $row->images[0]->filename : null);

        // mini content
        $row->mini_content = str_limit(strip_tags($row->content), 32);

        // url
        $row->url = url($row->slug);

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
                if ($key != 'slug') {
                    $store = self::_values($store, $value, $key);
                }
            }
        }

        $store['created_at'] = sql_date();
        $id = (int)self::insertGetId($store);

        // insert slug
        if ($id && isset($inputs['slug'])) {
            $slug = Slug::store([
                'source_id' => $id,
                'source_type' => 'page',
                'name' => $inputs['slug']
            ]);

            // revert
            if (!$slug) {
                self::remove($id);
            }
        }

        // upload cover
        self::_uploadImage($id, $inputs);

        if (!isset($store['is_email_to_subscriber'])) {
            $store['is_email_to_subscriber'] = 0;
        }

        if (!isset($store['draft'])) {
            $store['draft'] = 0;
        }

        return $id;
    }

    /**
     * Check for values
     *
     * @param $values
     * @param $value
     * @param $key
     * @return mixed
     */
    private static function _values($values, $value, $key)
    {
        if (!is_numeric($value)) {
            $values[$key] = $value;
        } else {
            $values[$key] = $value;
        }

        return $values;
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
        // delete all related images to page
        Image::destroySource($id, 'page');

        return (bool)self::destroy($id);
    }

    /**
     * Upload image
     *
     * @param $id
     * @param $inputs
     */
    private static function _uploadImage($id, $inputs)
    {
        if (isset($inputs['image']) && $id) {
            if ($inputs['image']) {
                try {
                    $page = self::single($id);

                    if ($page) {
                        // delete all previous cover
                        $images = Image::get([
                            'type' => 'page',
                            'source_id' => $page->id
                        ]);

                        // delete old cover
                        foreach ($images as $row) {
                            Image::remove($row->id);
                        }

                        // upload new cover
                        upload_image($inputs['image'], [
                            'user_id' => $page->user_id,
                            'source_id' => $page->id,
                            'title' => $page->name,
                            'type' => 'page',
                            'crop_auto' => true
                        ]);
                    }
                } catch (\Exception $e) {
                    error_logger('AdminPageStore:Class' . $e->getMessage());
                }
            }
        }
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
                if ($key != 'slug') {
                    $update = self::_values($update, $value, $key);
                }
            }
        }

        // update slug
        if ($id && isset($inputs['slug'])) {
            if ($inputs['slug']) {
                $slug = Slug::get([
                    'source_id' => $id,
                    'source_type' => 'page',
                    'single' => true
                ]);

                if ($slug) {
                    Slug::edit($slug->id, [
                        'name' => $inputs['slug']
                    ]);
                }
            }
        }

        // upload cover
        self::_uploadImage($id, $inputs);

        // if the content is null
        if (!isset($update['content'])) {
            $update['content'] = null;
        }

        if (!isset($update['is_email_to_subscriber'])) {
            $update['is_email_to_subscriber'] = 0;
        }

        if (!isset($update['draft'])) {
            $update['draft'] = 0;
        }

        // store to activity logs
        ActivityLog::store($id, self::$writable_columns, $query->first(), $inputs, (new self)->getTable());

        return (bool)$query->update($update);
    }
}
