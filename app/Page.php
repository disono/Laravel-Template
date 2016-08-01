<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Page extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'page_category_id', 'user_id', 'name', 'slug', 'content', 'template', 'draft'
    ];

    private static $params;

    /**
     * Get data
     *
     * @param array $params
     * @return null
     */
    public static function get($params = [])
    {
        $select[] = 'pages.*';

        $select[] = DB::raw('page_categories.name as category_name, page_categories.description as category_description, (' . self::_pageCategorySlug() . ') as category_slug');

        $select[] = DB::raw('(' . self::_pageSlug() . ') as slug');

        $query = self::select($select)
            ->join('page_categories', 'pages.page_category_id', '=', 'page_categories.id');

        if (isset($params['id'])) {
            $query->where('pages.id', $params['id']);
        }

        if (isset($params['draft'])) {
            $query->where('pages.draft', $params['draft']);
        }

        if (isset($params['user_id'])) {
            $query->where('pages.user_id', $params['user_id']);
        }

        if (isset($params['page_category_id'])) {
            $query->where('pages.page_category_id', $params['page_category_id']);
        }

        if (isset($params['slug'])) {
            $query->having('slug', '=', $params['slug']);
        }

        if (isset($params['category_slug'])) {
            $query->where('page_categories.slug', $params['category_slug']);
        }

        if (isset($params['search'])) {
            self::$params = $params;
            $query->having('pages.name', 'LIKE', '%' . self::$params['search'] . '%')
                ->orHaving('slug', 'LIKE', '%' . self::$params['search'] . '%')
                ->orHaving('pages.content', 'LIKE', '%' . self::$params['search'] . '%');
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
     * Store new data
     *
     * @param array $inputs
     * @return bool
     */
    public static function store($inputs = [])
    {
        $store = [];
        $columns = [
            'page_category_id', 'user_id', 'name', 'slug', 'content', 'template', 'draft'
        ];

        foreach ($inputs as $key => $value) {
            if (in_array($key, $columns)) {
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
        $columns = [
            'page_category_id', 'name', 'slug', 'content', 'template', 'draft'
        ];

        if (!$column_name) {
            $column_name = 'id';
        }

        if ($id && !is_array($column_name)) {
            $query = self::where($column_name, $id);
        } else {
            $i = 0;
            foreach ($column_name as $key => $value) {
                if (!in_array($key, $columns)) {
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
            if (in_array($key, $columns)) {
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

        return (bool)$query->update($update);
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
     * Check for values
     *
     * @param $values
     * @param $value
     * @param $key
     * @return mixed
     */
    private static function _values($values, $value, $key) {
        if (!is_numeric($value)) {
            if ($value) {
                $values[$key] = $value;
            }
        } else {
            $values[$key] = $value;
        }

        return $values;
    }

    /**
     * Upload image
     *
     * @param $id
     * @param $inputs
     */
    private static function _uploadImage($id, $inputs) {
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
            $query->images = Image::getAll([
                'type' => 'page',
                'source_id' => $query->id
            ]);

            // default image
            $query->cover = get_image((count($query->images)) ? $query->images[0]->filename : null);
        } else {
            foreach ($query as $row) {
                // images
                $row->images = Image::getAll([
                    'type' => 'page',
                    'source_id' => $row->id
                ]);

                // default image
                $row->cover = get_image((count($row->images)) ? $row->images[0]->filename : null);
            }
        }

        return $query;
    }
}
