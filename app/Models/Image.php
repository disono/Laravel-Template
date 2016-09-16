<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    private static $params;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'source_id', 'title', 'filename', 'type'
    ];

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
        $select[] = 'images.*';
        $query = self::select($select);

        if (isset($params['id'])) {
            $query->where('id', $params['id']);
        }

        if (isset($params['user_id'])) {
            $query->where('user_id', $params['user_id']);
        }

        if (isset($params['type'])) {
            $query->where('type', $params['type']);
        }

        if (isset($params['source_id'])) {
            $query->where('source_id', $params['source_id']);
        }

        if (isset($params['search'])) {
            self::$params = $params;
            $query->where(function ($query) {
                $query->where('id', 'LIKE', '%' . self::$params['search'] . '%')
                    ->orWhere('user_id', 'LIKE', '%' . self::$params['search'] . '%')
                    ->orWhere('title', 'LIKE', '%' . self::$params['search'] . '%')
                    ->orWhere('type', 'LIKE', '%' . self::$params['type'] . '%');
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

            // image path
            $query->path = get_image($query->filename, $query->type);

            // uploader full name
            $user = User::find($query->user_id);

            $query->full_name = ($user) ? $user->first_name . ' ' . $user->last_name : 'n/a';
        } else {
            foreach ($query as $row) {
                // image path
                $row->path = get_image($row->filename, $row->type);

                // uploader full name
                $user = User::find($row->user_id);

                $row->full_name = ($user) ? $user->first_name . ' ' . $user->last_name : 'n/a';
            }
        }

        return $query;
    }

    /**
     *
     * Get all data
     *
     * @param array $options
     * @return null
     */
    public static function getAll($options = [])
    {
        $options['all'] = true;
        return self::get($options);
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
            'user_id', 'source_id', 'title', 'filename', 'type'
        ];
        foreach ($inputs as $key => $value) {
            if (in_array($key, $columns)) {
                $store[$key] = $value;
            }
        }
        $store['created_at'] = sql_date();
        return (int)self::insertGetId($store);
    }

    /**
     * Make image default cover
     *
     * @param $id
     * @param $source_id
     */
    public static function defaultCover($id, $source_id)
    {
        // reset default cover
        self::where('source_id', $source_id)->update([
            'is_cover' => 0
        ]);

        self::where('id', $id)->update([
            'is_cover' => 1
        ]);
    }

    /**
     * Delete source
     *
     * @param $source_id
     * @param $type
     */
    public static function destroySource($source_id, $type)
    {
        $image = self::where('source_id', $source_id)->where('type', $type)->get();

        foreach ($image as $row) {
            self::remove($row->id);
        }
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
        $image = self::find($id);
        if ($image) {
            self::_deleteFile($image->filename);
        }

        return (bool)self::destroy($id);
    }

    /**
     * Delete image
     *
     * @param $file
     */
    private static function _deleteFile($file)
    {
        delete_file('private/img/' . $file);
    }
}
