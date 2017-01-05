<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PageCategory extends Model
{
    private static $params;

    protected static $writable_columns = [
        'name', 'description'
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
        $select[] = 'page_categories.*';

        $select[] = DB::raw('slugs.name as slug');

        $query = self::select($select)
            ->join('slugs', function ($join) {
                $join->on('page_categories.id', '=', 'slugs.source_id')
                    ->where('slugs.source_type', '=', 'page_category');
            });

        if (isset($params['id'])) {
            $query->where('page_categories.id', $params['id']);
        }

        if (isset($params['slug'])) {
            $query->where('slugs.name', $params['slug']);
        }

        if (isset($params['search'])) {
            self::$params = $params;
            $query->Where(function ($query) {
                $query->where('page_categories.name', 'LIKE', '%' . self::$params['search'] . '%')
                    ->orWhere('slugs.name', 'LIKE', '%' . self::$params['search'] . '%')
                    ->orWhere('page_categories.description', 'LIKE', '%' . self::$params['search'] . '%');
            });
        }

        $query->orderBy('page_categories.name', 'DESC');

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
                if ($key != 'slug') {
                    $store[$key] = $value;
                }
            }
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
                $slug = Slug::get([
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

        return (bool)$query->update($update);
    }
}
