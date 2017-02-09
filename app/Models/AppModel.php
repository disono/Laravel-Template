<?php
/**
 * Created by PhpStorm.
 * User: archi
 * Date: 2/4/2017
 * Time: 12:48 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AppModel extends Model
{
    /**
     * Exclude and Include
     *
     * @param $query
     * @param $params
     * @param array $writable_columns
     * @param $table_name
     * @return mixed
     */
    public static function _excInc($query, $params, $writable_columns = [], $table_name = null)
    {
        $table_name = ($table_name) ? $table_name . '.' : '';

        // ['column_name' => [value, value, value]]
        if (isset($params['exclude'])) {
            $exclude = $params['exclude'];

            foreach ($exclude as $key => $value) {
                if (in_array($key, $writable_columns)) {
                    if (is_array($value)) {
                        $query->whereNotIn($table_name . $key, $value);
                    }
                }
            }
        }

        // ['column_name' => [value, value, value]]
        if (isset($params['include'])) {
            $exclude = $params['include'];

            foreach ($exclude as $key => $value) {
                if (in_array($key, $writable_columns)) {
                    if (is_array($value)) {
                        $query->whereIn($table_name . $key, $value);
                    }
                }
            }
        }

        return $query;
    }

    /**
     * Search parameters
     *
     * If errors occured on searching please check your writable columns if exists on table
     *
     * @param $query
     * @param $params
     * @param array $writable_columns
     * @param null $table_name
     * @param array $custom_params
     * @return mixed
     */
    public static function _search($query, $params, $writable_columns = [], $table_name = null, $custom_params = [])
    {
        if (isset($params['search'])) {
            // always check for values
            $query->where(function ($query) use ($params, $writable_columns, $table_name, $custom_params) {
                $num = 0;
                $table_name = ($table_name) ? $table_name . '.' : '';

                // default columns table search
                foreach ($writable_columns as $row) {
                    $column_name = $table_name . $row;
                    $query = self::_searchQuery($num, $query, $column_name, $params['search']);

                    $num++;
                }

                // custom search parameters
                foreach ($custom_params as $row) {
                    $column_name = $row;
                    $query = self::_searchQuery($num, $query, $column_name, $params['search']);

                    $num++;
                }
            });
        } else if (isset($params['search_only'])) {
            // search only for specific columns
            $search_only = $params['search_only'];

            if (is_array($search_only)) {
                $query->where(function ($query) use ($params, $writable_columns, $table_name, $custom_params, $search_only) {
                    $num = 0;
                    $table_name = ($table_name) ? $table_name . '.' : '';

                    foreach ($search_only as $column_name => $value) {
                        $column_name = $table_name . $column_name;
                        $query = self::_searchQuery($num, $query, $column_name, $value);

                        $num++;
                    }
                });
            }
        }

        return $query;
    }

    /**
     * Search
     *
     * @param $index
     * @param $query
     * @param $column_name
     * @param $search
     * @return mixed
     */
    private static function _searchQuery($index, $query, $column_name, $search)
    {
        // check values to search must not be empty
        if ($search != '' && $search != null) {
            if (!$index) {
                $query->where($column_name, 'LIKE', '%' . $search . '%');
            } else {
                $query->orWhere($column_name, 'LIKE', '%' . $search . '%');
            }
        }

        return $query;
    }

    /**
     * Where equal
     * Note always check the default writable columns for conflicting column names
     *
     * @param $query
     * @param $params
     * @param array $writable_columns
     * @param null $table_name
     * @param array $custom_columns
     * @return mixed
     */
    public static function _whereEqual($query, $params, $writable_columns = [], $table_name = null, $custom_columns = [])
    {
        $table_name = ($table_name) ? $table_name . '.' : '';

        // current id of the table index
        if (isset($params['id'])) {
            $query->where($table_name . 'id', $params['id']);
        }

        // writable columns
        foreach ($writable_columns as $row) {
            if (isset($params[$row])) {
                $query->where($table_name . $row, $params[$row]);
            }
        }

        // custom columns
        //  array(['custom_column_name' => $params['key']])
        foreach ($custom_columns as $column => $params_key) {
            if (isset($params[$params_key])) {
                $query->where($column, $params[$params_key]);
            }
        }

        // date range
        // date_range_from and date_range_to
        // custom date_range_name unless use is created_at
        if (isset($params['date_range_from']) && isset($params['date_range_to'])) {
            $date_name = (isset($params['date_range_name'])) ? $params['date_range_name'] : $table_name . 'created_at';

            $query->whereBetween(DB::raw('DATE(' . $date_name . ')'), [sql_date($params['date_range_from'], true), sql_date($params['date_range_to'], true)]);
        }

        // fetch the current data today
        // if $fetch_today is boolean get the data today unless specify the date
        if (isset($params['fetch_today'])) {
            $data_name_today = (isset($params['fetch_today_name'])) ? $params['fetch_today_name'] : $table_name . 'created_at';
            $current_date = ($params['fetch_today'] === true || is_numeric($params['fetch_today'])) ? 'CURDATE()' : sql_date($params['fetch_today'], true);

            $query->whereRaw('DATE(' . $data_name_today . ') = ' . $current_date);
        }

        return $query;
    }

    /**
     * Check values of checkbox
     *
     * @param array $inputs
     * @param $checkbox_list $list
     * @return array
     */
    public static function _checkBoxValues($inputs = [], $checkbox_list = [])
    {
        foreach ($checkbox_list as $item) {
            if (!isset($inputs[$item])) {
                $inputs[$item] = 0;
            } else {
                $inputs[$item] = 1;
            }
        }

        return $inputs;
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
        } else {
            foreach ($query as $row) {

            }
        }

        return $query;
    }
}