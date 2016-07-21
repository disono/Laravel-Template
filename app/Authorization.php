<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: http://www.webmons.com
 * License: Apache 2.0
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class Authorization extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'identifier', 'description'
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
        $select[] = 'authorizations.*';
        $query = self::select($select);

        if (isset($params['id'])) {
            $query->where('id', $params['id']);
        }

        if (isset($params['identifier'])) {
            $query->where('identifier', $params['identifier']);
        }

        if (isset($params['search'])) {
            self::$params = $params;
            $query->where(function ($query) {
                $query->where('name', 'LIKE', '%' . self::$params['search'] . '%')
                    ->orWhere('identifier', 'LIKE', '%' . self::$params['search'] . '%')
                    ->orWhere('description', 'LIKE', '%' . self::$params['search'] . '%');
            });
        }

        if (isset($params['exclude'])) {
            $exclude = $params['exclude'];
            foreach ($exclude['val'] as $key => $val) {
                $query->where($exclude['key'], '<>', $val);
            }
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
            'name', 'identifier', 'description'
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
            'name', 'identifier', 'description'
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
