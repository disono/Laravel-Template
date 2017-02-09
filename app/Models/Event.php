<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */

namespace App\Models;

class Event extends AppModel
{
    protected static $writable_columns = [
        'user_id', 'name', 'slug', 'content', 'template',
        'start_date', 'start_time', 'end_date', 'end_time',
        'draft'
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
        $query = self::select($select);

        // where equal
        $query = self::_whereEqual($query, $params, self::$writable_columns, $table_name);

        // exclude and include
        $query = self::_excInc($query, $params, self::$writable_columns, $table_name);

        // search
        $query = self::_search($query, $params, self::$writable_columns, $table_name);

        $query->orderBy($table_name . '.created_at', 'DESC');

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
            if ($key == 'start_date') {
                $values[$key] = sql_date($value, true);
            } else if ($key == 'start_time') {
                $values[$key] = sql_time($value);
            } else if ($key == 'end_date') {
                $values[$key] = sql_date($value, true);
            } else if ($key == 'end_time') {
                $values[$key] = sql_time($value);
            } else {
                $values[$key] = $value;
            }
        } else {
            $values[$key] = $value;
        }
        return $values;
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

        foreach ($inputs as $key => $value) {
            if (in_array($key, self::$writable_columns)) {
                $store = self::_values($store, $value, $key);
            }
        }
        $store['created_at'] = sql_date();
        $id = (int)self::insertGetId($store);

        // upload cover
        self::_uploadImage($id, $inputs);

        return $id;
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
                    $event = self::single($id);
                    if ($event) {
                        // delete all previous cover
                        $images = Image::get([
                            'type' => 'event',
                            'source_id' => $event->id
                        ]);

                        // delete old cover
                        foreach ($images as $row) {
                            Image::remove($row->id);
                        }

                        // upload new cover
                        upload_image($inputs['image'], [
                            'user_id' => $event->user_id,
                            'source_id' => $event->id,
                            'title' => $event->name,
                            'type' => 'event',
                            'crop_auto' => true
                        ]);
                    }
                } catch (\Exception $e) {
                    error_logger('AdminEventStore:Class' . $e->getMessage());
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
    public static function _format($query, $params = [])
    {
        if (isset($params['single'])) {
            if (!$query) {
                return null;
            }

            // images
            $query->images = Image::getAll([
                'type' => 'event',
                'source_id' => $query->id
            ]);

            // default image
            $query->cover = get_image((count($query->images)) ? $query->images[0]->filename : null);

            // format
            $query->formatted_start_date = ($query->start_date) ? date('F d, Y', strtotime($query->start_date)) : null;
            $query->formatted_end_date = ($query->end_date) ? date('F d, Y', strtotime($query->end_date)) : null;
            $query->formatted_start_time = ($query->start_time) ? date('h:i A', strtotime($query->start_time)) : null;
            $query->formatted_end_time = ($query->end_time) ? date('h:i A', strtotime($query->end_time)) : null;
        } else {
            foreach ($query as $row) {
                // images
                $row->images = Image::getAll([
                    'type' => 'event',
                    'source_id' => $row->id
                ]);

                // default image
                $row->cover = get_image((count($row->images)) ? $row->images[0]->filename : null);

                // format
                $row->formatted_start_date = ($row->start_date) ? date('F d, Y', strtotime($row->start_date)) : null;
                $row->formatted_end_date = ($row->end_date) ? date('F d, Y', strtotime($row->end_date)) : null;
                $row->formatted_start_time = ($row->start_time) ? date('h:i A', strtotime($row->start_time)) : null;
                $row->formatted_end_time = ($row->end_time) ? date('h:i A', strtotime($row->end_time)) : null;
            }
        }
        return $query;
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
        // delete all related images to event
        Image::destroySource($id, 'event');

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
                $update = self::_values($update, $value, $key);
            }
        }

        // upload cover
        self::_uploadImage($id, $inputs);

        // store to activity logs
        ActivityLog::store($id, self::$writable_columns, $query->first(), $inputs, (new self)->getTable());

        return (bool)$query->update($update);
    }
}
