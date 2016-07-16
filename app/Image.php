<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: http://www.webmons.com
 * License: Apache 2.0
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'title', 'filename', 'type'
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

        if (isset($params['search'])) {
            self::$params = $params;
            $query->where(function ($query) {
                $query->where('id', 'LIKE', '%' . self::$params['search'] . '%')
                    ->orWhere('user_id', 'LIKE', '%' . self::$params['search'] . '%')
                    ->orWhere('title', 'LIKE', '%' . self::$params['search'] . '%')
                    ->orWhere('type', 'LIKE', '%' . self::$params['type'] . '%');
            });
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
            'user_id', 'title', 'filename', 'type'
        ];
        foreach ($inputs as $key => $value) {
            if (in_array($key, $columns)) {
                $store[$key] = $value;
            }
        }
        $store['created_at'] = sql_date();
        return (bool)Image::insertGetId($store);
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
        $image = Image::find($id);
        if ($image) {
            delete_file('private/img/' . $image->filename);
        }

        return (bool)self::destroy($id);
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
}
