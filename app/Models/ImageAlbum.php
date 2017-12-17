<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models;

class ImageAlbum extends AppModel
{
    protected static $table_name = 'image_albums';
    protected static $writable_columns = [
        'name', 'slug', 'description'
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
     * Add formatting to data
     *
     * @param $row
     * @return mixed
     */
    public static function _dataFormatting($row)
    {
        $images = Image::fetch([
            'type' => 'album',
            'source_id' => $row->id,
            'all' => true
        ]);

        // count files
        $row->count_images = count($images);

        // images
        $row->images = $images;

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

        // store to activity logs
        ActivityLog::log($id, self::$writable_columns, $query->first(), $inputs, (new self)->getTable());

        return (bool)$query->update($update);
    }
}
