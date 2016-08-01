<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AuthHistory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'ip', 'platform', 'type', 'content'
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
        $select[] = 'auth_histories.*';

        $select[] = DB::raw('users.first_name, users.last_name, CONCAT(users.first_name, " ", users.last_name) as full_name, ' .
            '(SELECT name FROM slugs WHERE source_id = users.id AND source_type = "user") as username');

        $query = self::select($select)
            ->join('users', 'auth_histories.user_id', '=', 'users.id');

        if (isset($params['user_id'])) {
            $query->where('auth_histories.user_id', $params['user_id']);
        }

        if (isset($params['type'])) {
            $query->where('auth_histories.type', $params['type']);
        }

        if (isset($params['search'])) {
            self::$params = $params;
            $query->Where(function ($query) {
                $query->where('auth_histories.type', 'LIKE', '%' . self::$params['search'] . '%')
                    ->orWhere('auth_histories.content', 'LIKE', '%' . self::$params['search'] . '%')
                    ->orWhere('auth_histories.platform', 'LIKE', '%' . self::$params['search'] . '%');
            });
        }

        $query->orderBy('auth_histories.created_at', 'DESC');

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
     * Store new data
     *
     * @param array $inputs
     * @return bool
     */
    public static function store($inputs = [])
    {
        $store = [];
        $columns = [
            'user_id', 'ip', 'platform', 'type', 'content'
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
        $columns = [
            'user_id', 'ip', 'platform', 'type', 'content'
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
                $update[$key] = $value;
            }
        }

        return (bool)$query->update($update);
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
}
