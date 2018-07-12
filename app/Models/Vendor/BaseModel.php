<?php
/**
 * @author          Archie, Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (webmons.com), 2016-2018
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models\Vendor;

use App\Models\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BaseModel extends Model
{
    protected static $writableColumns = [];
    protected static $tableName = null;
    protected static $params = [];

    protected static $files = [];
    protected static $imageOptions = [];

    protected static $inputDates = [];
    protected static $inputDateTimes = [];
    protected static $inputIntegers = [];
    protected static $inputBooleans = [];
    protected static $inputCrypt = [];

    /**
     * Get writable columns set
     *
     * @return array
     */
    public static function getColumns()
    {
        return static::$writableColumns;
    }

    /**
     * Get all data no pagination
     *
     * @param array $params
     * @return null
     */
    public static function fetchAll($params = [])
    {
        $params['all'] = true;
        static::$params = $params;
        return static::fetch($params);
    }

    /**
     * Get data
     *
     * @param array $params
     * @return null
     */
    public static function fetch($params = [])
    {
        static::$params = $params;

        $select[] = static::$tableName . '.*';
        $select = static::rawSelects($select);

        // custom select
        $query = DB::table(static::$tableName)->select($select);

        // where equal
        $query = static::rawWhere($query);

        // exclude and include
        $query = static::rawExcludeInclude($query);

        // search
        $query = static::rawSearch($query);

        // custom filter
        $query = static::rawFilters($query);

        // add formatting
        return static::readyFormatting($query);
    }

    /**
     * Custom selects
     *
     * @param array $select
     * @return array
     */
    public static function rawSelects($select = [])
    {
        foreach (static::rawQuerySelects() as $key => $q) {
            $select[] = DB::raw('(' . $q . ') AS ' . $key);
        }

        return $select;
    }

    /**
     * Custom queries
     *
     * @param $key
     * @return mixed|null
     */
    protected static function rawQuerySelects($key = null)
    {
        $queries = static::rawQuerySelectList();

        if ($key === null) {
            return $queries;
        }

        return $queries[$key] ?? null;
    }

    /**
     * List of select
     *
     * @return array
     */
    protected static function rawQuerySelectList()
    {
        return [];
    }

    /**
     * Raw where (writable columns equal, date range, current date)
     *
     * @param $query
     * @param array $customColumns
     * @return mixed
     */
    public static function rawWhere($query, $customColumns = [])
    {
        $tableName = (static::$tableName) ? static::$tableName . '.' : '';

        // current id of the table index
        if (is_numeric(self::requestParams('id'))) {
            $query->where($tableName . 'id', static::$params['id']);
        }

        foreach (static::$writableColumns as $row) {
            // writable columns (non-custom)
            if (isset(static::$params[$row])) {
                $query->where($tableName . $row, static::$params[$row]);
            }

            // default custom columns
            $defaultCustomColumn = self::rawQuerySelects($row);
            if ($defaultCustomColumn) {
                $query->where(DB::raw($defaultCustomColumn), static::$params[$row]);
            }
        }

        // custom columns
        // array(['custom_column_name' => $params['key']])
        foreach ($customColumns as $column => $params_key) {
            if (isset(static::$params[$params_key])) {
                $query->where($column, static::$params[$params_key]);
            }
        }

        // NOTE: https://www.w3schools.com/sql/func_mysql_date_format.asp
        // date range
        // date_range_from and date_range_to
        // custom date_range_name unless use is created_at
        if (self::requestParams('date_range_from') && self::requestParams('date_range_to')) {
            $date_name = static::$params['date_range_name'] ?? ($tableName . 'created_at');
            $query->whereBetween(DB::raw('DATE(' . $date_name . ')'), [
                sqlDate(static::$params['date_range_from'], true),
                sqlDate(static::$params['date_range_to'], true)
            ]);
        }

        // fetch today
        // if $fetch_today is boolean get the data today unless specify the date
        if (self::requestParams('fetch_today')) {
            $data_name_today = static::$params['fetch_today_name'] ?? ($tableName . 'created_at');
            $current_date = (static::$params['fetch_today'] === true || is_numeric(static::$params['fetch_today'])) ?
                'CURDATE()' : sqlDate(static::$params['fetch_today'], true);

            $query->whereRaw('DATE(' . $data_name_today . ') = ' . $current_date);
        }

        // fetch by year e.g. 2018, 2019
        if (is_numeric(self::requestParams('raw_year'))) {
            $date_name = static::$params['raw_date_name'] ?? ($tableName . 'created_at');
            $query->whereRaw('DATE_FORMAT(' . $date_name . ', "%Y") = "' . self::requestParams('raw_year') . '"');
        }

        // fetch by month e.g. June, February
        if (self::requestParams('raw_month')) {
            $date_name = static::$params['raw_date_name'] ?? ($tableName . 'created_at');
            $query->whereRaw('DATE_FORMAT(' . $date_name . ', "%M") = "' . self::requestParams('raw_month') . '"');
        }

        // fetch by day e.g. 01, 11
        if (is_numeric(self::requestParams('raw_day'))) {
            $date_name = static::$params['raw_date_name'] ?? ($tableName . 'created_at');
            $query->whereRaw('DATE_FORMAT(' . $date_name . ', "%d") = "' . self::requestParams('raw_day') . '"');
        }

        return $query;
    }

    /**
     * Get parameters
     *
     * @param $key
     * @return mixed|null
     */
    protected static function requestParams($key)
    {
        return static::$params[$key] ?? null;
    }

    /**
     * Raw where for including and excluding
     *
     * @param $query
     * @return mixed
     */
    public static function rawExcludeInclude($query)
    {
        $tableName = (static::$tableName) ? static::$tableName . '.' : '';
        $columns = static::$writableColumns;
        $columns[] = 'id';

        // ['column_name' => [value, value, value]]
        if (isset(static::$params['exclude'])) {
            $exclude = static::$params['exclude'];
            $exclude = self::extractIncExcData($exclude);

            foreach ($exclude as $key => $value) {
                if (in_array($key, $columns)) {
                    if (is_array($value)) {
                        $query->whereNotIn($tableName . $key, $value);
                    }
                }
            }
        }

        // ['column_name' => [value, value, value]]
        if (isset(static::$params['include'])) {
            $include = static::$params['include'];
            $include = self::extractIncExcData($include);

            foreach ($include as $key => $value) {
                if (in_array($key, $columns)) {
                    if (is_array($value)) {
                        $query->whereIn($tableName . $key, $value);
                    }
                }
            }
        }

        return $query;
    }

    /**
     * Extract data
     * string e.g. column_name:value1,value2|column_name:value1,value2
     *
     * @param $data
     * @return array|string
     */
    protected static function extractIncExcData($data)
    {
        // if string e.g. column_name:value1,value2|column_name:value1,value2
        if (is_string($data)) {
            $divider = explode('|', $data);

            $clean = [];
            foreach ($divider as $extract) {
                $column = explode(':', $extract);

                if (count($column) == 2) {
                    $clean[$column[0]] = explode(',', $column[1]);
                }
            }

            $data = $clean;
        }

        return $data;
    }

    /**
     * Search parameters
     *
     * If errors occurred on searching please check your writable columns if exists on table
     *
     * @param $query
     * @return mixed
     */
    public static function rawSearch($query)
    {
        if (self::requestParams('search')) {
            // always check for values
            $query->where(function ($query) {
                $num = 0;
                // default columns table search
                foreach (static::$writableColumns as $row) {
                    $column_name = static::$tableName . '.' . $row;
                    $query = self::searchQuery($num, $query, $column_name, static::$params['search']);
                    $num++;
                }

                // custom search parameters
                foreach (static::rawQuerySelectList() as $column_name) {
                    $query = self::searchQuery($num, $query, DB::raw('(' . $column_name . ')'), static::$params['search']);
                    $num++;
                }
            });
        } else if (self::requestParams('search_only')) {
            // search only for specific columns
            $search_only = static::$params['search_only'];

            if (is_array($search_only)) {
                $query->where(function ($query) use ($search_only) {
                    // custom search parameters
                    $_customSearch = static::rawQuerySelectList();

                    $num = 0;
                    foreach ($search_only as $column_name => $value) {
                        $column_name = static::$tableName . $column_name;
                        $query = self::searchQuery($num, $query, $column_name, $value);
                        $num++;

                        if (isset($_customSearch[$column_name])) {
                            $query = self::searchQuery($num, $query, DB::raw('(' . $_customSearch[$column_name] . ')'), $value);
                        }
                    }
                });
            }
        }

        $orderByColumn = static::$params['order_by_column'] ?? 'updated_at';
        $orderByDirection = static::$params['order_by_direction'] ?? 'desc';
        $query->orderBy($orderByColumn, $orderByDirection);

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
    protected static function searchQuery($index, $query, $column_name, $search)
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
     * Custom filters
     *
     * @param $query
     * @return mixed
     */
    public static function rawFilters($query)
    {
        return $query;
    }

    /**
     * Ready for formatting data
     *
     * @param $query
     * @return mixed
     */
    protected static function readyFormatting($query)
    {
        if (isset(static::$params['object'])) {
            return $query;
        } else if (isset(static::$params['single'])) {
            return static::format($query->first());
        } else if (isset(static::$params['all'])) {
            return static::format($query->get());
        } else {
            $perPage = (int)__settings('pagination')->value;
            $perPage = ($perPage) ? $perPage : 12;
            return static::format($query->paginate($perPage));
        }
    }

    /**
     * Add formatting on data
     *
     * @param $query
     * @return null
     */
    protected static function format($query)
    {
        if (isset(static::$params['single'])) {
            if (!$query) {
                return null;
            }

            static::dataFormatting($query);
        } else {
            foreach ($query as $row) {
                static::dataFormatting($row);
            }
        }

        return $query;
    }

    /**
     * Add formatting to data
     *
     * @param $row
     * @return mixed
     */
    protected static function dataFormatting($row)
    {
        return $row;
    }

    /**
     * Store new data
     *
     * @param array $inputs
     * @param bool $checkDefaults
     * @return bool
     */
    public static function store($inputs = [], $checkDefaults = true)
    {
        $store = [];
        foreach ($inputs as $key => $value) {
            if (in_array($key, static::$writableColumns) && $key !== 0 && $key !== null) {
                $value = ($value === '' || $value === null) ? null : $value;
                $store[$key] = $value;
            }
        }

        // add defaults
        if ($checkDefaults === true) {
            $store = self::defaultInputs($store);
        }

        // other custom action
        if (!static::actionStoreBefore(static::$tableName, $inputs)) {
            return null;
        }

        // formatting before saving
        $store = self::formatStore(static::$tableName, $store);

        $store['created_at'] = sqlDate();
        $save = static::single(self::insertGetId($store));

        // process files
        if ($save) {
            self::_processStoreFiles($inputs, $save);
            static::actionStoreAfter($save, $store);
        }

        return $save;
    }

    /**
     * Default inputs
     *
     * @param array $inputs
     * @param array $defaults
     * @return array
     */
    protected static function defaultInputs($inputs = [], $defaults = [])
    {
        // clean dates
        foreach (static::$inputDates as $key) {
            if (isset($inputs[$key])) {
                if ($inputs[$key] !== null && $inputs[$key] !== '') {
                    $inputs[$key] = sqlDate($inputs[$key], true);
                } else {
                    unset($inputs[$key]);
                }
            }
        }

        // clean dates with time
        foreach (static::$inputDateTimes as $key) {
            if (isset($inputs[$key])) {
                if ($inputs[$key] !== null && $inputs[$key] !== '') {
                    $inputs[$key] = sqlDate($inputs[$key]);
                } else {
                    unset($inputs[$key]);
                }
            }
        }

        // clean integers with zero
        foreach (static::$inputIntegers as $key) {
            if (isset($inputs[$key])) {
                if ($inputs[$key] !== null && $inputs[$key] !== '' && is_numeric($inputs[$key])) {
                    $inputs[$key] = (int)$inputs[$key];
                } else {
                    $inputs[$key] = 0;
                }
            } else {
                $inputs[$key] = 0;
            }
        }

        // clean boolean
        foreach (static::$inputBooleans as $key) {
            if (isset($inputs[$key])) {
                $inputs[$key] = ($inputs[$key] == 1) ? 1 : 0;
            } else {
                $inputs[$key] = 0;
            }
        }

        // BCrypt
        foreach (static::$inputCrypt as $key) {
            if (isset($inputs[$key])) {
                if ($inputs[$key] !== null && $inputs[$key] !== '') {
                    $inputs[$key] = bcrypt($inputs[$key]);
                } else {
                    unset($inputs[$key]);
                }
            } else {
                unset($inputs[$key]);
            }
        }

        return $inputs;
    }

    /**
     * Process file uploads from store
     *
     * @param $inputs
     * @param $save
     */
    public static function _processStoreFiles($inputs, $save)
    {
        foreach (static::$files as $file) {
            if (isset($inputs[$file])) {
                $save->_files = fileUpload($inputs[$file], 'private', static::$imageOptions,
                    null, null, static::$tableName, $save->id);
            }
        }
    }

    /**
     * Custom method for storing
     *
     * @param $tableName
     * @param $inputs
     * @return bool
     */
    public static function actionStoreBefore($tableName, $inputs)
    {
        return true;
    }

    /**
     * After successful save
     *
     * @param $query
     * @param $inputs
     */
    public static function actionStoreAfter($query, $inputs)
    {

    }

    /**
     * Custom formatting for storing
     *
     * @param $tableName
     * @param $inputs
     * @return mixed
     */
    public static function formatStore($tableName, $inputs)
    {
        return $inputs;
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

        return static::fetch([
            'single' => true,
            $column => $id
        ]);
    }

    /**
     * Update data
     *
     * @param $id
     * @param array $inputs
     * @param null $columnName
     * @param bool $checkDefaults
     * @return bool
     */
    public static function edit($id, $inputs = [], $columnName = null, $checkDefaults = true)
    {
        $update = [];
        $query = self::rawFetch($id, $columnName);

        if (!$query->first()) {
            return false;
        }

        // clean inputs
        foreach ($inputs as $key => $value) {
            if (in_array($key, static::$writableColumns) && $key !== 0 && $key !== null) {
                $value = ($value === '' || $value === null) ? null : $value;
                $update[$key] = $value;
            }
        }

        // add defaults
        if ($checkDefaults === true) {
            $update = self::defaultInputs($update, $query->first());
        }

        // other custom action
        if (!static::actionEditBefore(static::$tableName, $query->first(), $inputs)) {
            return false;
        }

        // formatting before saving
        $update = self::formatEdit(static::$tableName, $update);

        $update['updated_at'] = sqlDate();
        $_u = (bool)$query->update($update);

        // process files
        self::_processEditFiles($inputs, $update, static::single($query->first()->id));

        return $_u;
    }

    /**
     * Raw fetch
     *
     * @param $id
     * @param null $columnName
     * @return bool|\Illuminate\Database\Query\Builder|null
     */
    public static function rawFetch($id, $columnName = null)
    {
        $query = null;

        if (!$columnName) {
            $columnName = 'id';
        }

        // filters for editing
        if ($id && !is_array($columnName)) {
            $query = DB::table(static::$tableName)->where($columnName, $id);
        } else {
            $i = 0;
            foreach ($columnName as $key => $value) {
                if (!in_array($key, static::$writableColumns)) {
                    return false;
                }

                if (!$i) {
                    $query = DB::table(static::$tableName)->where($key, $value);
                } else {
                    if ($query) {
                        $query->where($key, $value);
                    }
                }

                $i++;
            }
        }

        return $query;
    }

    /**
     * Process files for edit
     *
     * @param $inputs
     * @param $update
     * @param $_data
     */
    public static function _processEditFiles($inputs, $update, $_data)
    {
        if ($_data) {
            // process files
            $_files = [];
            foreach (static::$files as $file) {
                if (isset($inputs[$file])) {
                    $_files = fileUpload($inputs[$file], 'private', static::$imageOptions,
                        null, null, static::$tableName, $_data->id);
                }
            }

            $_data->_files = $_files;
            static::actionEditAfter($_data, $update);
        }
    }

    /**
     * Custom method for editing
     *
     * @param $tableName
     * @param $query
     * @param $inputs
     * @return bool
     */
    public static function actionEditBefore($tableName, $query, $inputs)
    {
        return true;
    }

    /**
     * After updating the data
     *
     * @param $query
     * @param $inputs
     */
    public static function actionEditAfter($query, $inputs)
    {

    }

    /**
     * Custom formatting for editing
     *
     * @param $tableName
     * @param $inputs
     * @return mixed
     */
    public static function formatEdit($tableName, $inputs)
    {
        return $inputs;
    }

    /**
     * Delete data
     *
     * @param $id
     * @param null $columnName
     *
     * @return integer
     */
    public static function remove($id, $columnName = null)
    {
        // check if exists or tried to delete the users authorization
        $_r = self::rawFetch($id, $columnName)->first();
        if (!$_r) {
            return false;
        }

        if (!static::actionRemove($_r)) {
            return false;
        }

        // remove
        $save = self::rawFetch($id, $columnName)->delete();

        // delete and remove all files based on table name and id
        if ($save) {
            foreach (File::fetch(['table_name' => static::$tableName, 'table_id' => $id]) as $file) {
                if (fileDestroy('private/' . $file->file_name)) {
                    File::remove($file->id);
                }
            }
        }

        return $save;
    }

    /**
     * Custom action remove
     *
     * @param $query
     * @return bool
     */
    public static function actionRemove($query)
    {
        return true;
    }
}