<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models\Vendor;

use App\Models\ActivityLog;
use App\Models\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class BaseModel extends Model
{
    protected $writableColumns = [];
    protected $tableName = null;
    protected $params = [];

    // single: [filename]
    // multiple: [[filename, table]]
    protected $files = [];
    // [filename => [tag => string, crop_width => num, crop_height => num, crop_auto => boolean, width => num,
    // height => num, heightRatio => num, widthRatio => num, quality => int]]
    protected $fileOptions = [];

    protected $inputDates = [];
    protected $inputDateTimes = [];
    protected $inputCrypt = [];

    protected $inputIntegers = [];
    protected $inputNumeric = [];
    protected $inputBooleans = [];

    /**
     * Get writable columns set
     *
     * @return array
     */
    public function getWritableColumns()
    {
        return $this->writableColumns;
    }

    /**
     * Remove all boolean to update
     */
    public function clearBoolean()
    {
        $this->inputBooleans = [];
    }

    /**
     * Remove all integer to update
     */
    public function clearInteger()
    {
        $this->inputIntegers = [];
    }

    /**
     * Remove all numeric to update
     */
    public function clearNumeric()
    {
        $this->inputNumeric = [];
    }

    /**
     * Get all data no pagination
     *
     * @param array $params
     * @return null
     */
    public function fetchAll($params = [])
    {
        $params['all'] = true;
        $this->params = $params;
        return $this->fetch($params);
    }

    /**
     * Get data
     *
     * @param array $params
     * @return null
     */
    public function fetch($params = [])
    {
        $this->params = $params;

        $select[] = $this->tableName . '.*';
        $select = $this->rawSelects($select);

        // custom select
        $query = DB::table($this->tableName)->select($select);

        // where equal
        $query = $this->rawWhere($query);

        // exclude and include
        $query = $this->rawExcludeInclude($query);

        // search
        $query = $this->rawSearch($query);

        // custom filter
        $query = $this->rawFilters($query);

        // add formatting
        return $this->readyFormatting($query);
    }

    /**
     * Custom selects
     *
     * @param array $select
     * @return array
     */
    public function rawSelects($select = [])
    {
        foreach ($this->rawQuerySelects() as $key => $q) {
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
    protected function rawQuerySelects($key = null)
    {
        $queries = $this->rawQuerySelectList();

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
    protected function rawQuerySelectList()
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
    public function rawWhere($query, $customColumns = [])
    {
        $tableName = ($this->tableName) ? $this->tableName . '.' : '';

        // current id of the table index
        if (is_numeric($this->hasParams('id'))) {
            $query->where($tableName . 'id', $this->params['id']);
        }

        // equal fetch
        foreach ($this->writableColumns as $row) {
            // writable columns (non-custom)
            if (key_exists($row, $this->params)) {
                if ($this->_allowNull($row) === true) {
                    $query->where($tableName . $row, $this->params[$row]);
                }
            }

            // default custom columns
            $defaultCustomColumn = $this->rawQuerySelects($row);
            if ($this->_allowNullWithVal($defaultCustomColumn)) {
                $query->where(DB::raw($defaultCustomColumn), $this->params[$row]);
            }
        }

        // custom search
        foreach ($this->rawQuerySelectList() as $key => $value) {
            if ($this->_allowNullWithVal($value) && $this->hasParams($key) !== null) {
                $query->where(DB::raw($value), $this->params[$key]);
            }
        }

        // custom columns
        // array(['custom_column_name' => $params['key']])
        foreach ($customColumns as $column => $params_key) {
            if (key_exists($params_key, $this->params[$params_key])) {
                if ($this->_allowNull($params_key) === true) {
                    $query->where($column, $this->params[$params_key]);
                }
            }
        }

        // date range
        // date_range_from and date_range_to
        // custom date_range_name unless use is created_at
        // NOTE: https://www.w3schools.com/sql/func_mysql_date_format.asp
        if ($this->hasParams('date_range_from') && $this->hasParams('date_range_to')) {
            $date_name = $this->params['date_range_name'] ?? ($tableName . 'created_at');

            $query->whereBetween(DB::raw('DATE(' . $date_name . ')'), [
                sqlDate($this->params['date_range_from'], true),
                sqlDate($this->params['date_range_to'], true)
            ]);
        }

        // fetch today
        // if $fetch_today is boolean get the data today unless specify the date
        if ($this->hasParams('fetch_today')) {
            $data_name_today = $this->params['fetch_today_name'] ?? ($tableName . 'created_at');
            $current_date = ($this->params['fetch_today'] === true || is_numeric($this->params['fetch_today'])) ?
                'CURDATE()' : sqlDate($this->params['fetch_today'], true);

            $query->whereRaw('DATE(' . $data_name_today . ') = ' . $current_date);
        }

        // fetch by year e.g. 2018, 2019
        if (is_numeric($this->hasParams('raw_year'))) {
            $date_name = $this->params['raw_date_name'] ?? ($tableName . 'created_at');
            $query->whereRaw('DATE_FORMAT(' . $date_name . ', "%Y") = "' . $this->hasParams('raw_year') . '"');
        }

        // fetch by month e.g. June, February
        if ($this->hasParams('raw_month')) {
            $date_name = $this->params['raw_date_name'] ?? ($tableName . 'created_at');
            $query->whereRaw('DATE_FORMAT(' . $date_name . ', "%M") = "' . $this->hasParams('raw_month') . '"');
        }

        // fetch by day e.g. 01, 11
        if (is_numeric($this->hasParams('raw_day'))) {
            $date_name = $this->params['raw_date_name'] ?? ($tableName . 'created_at');
            $query->whereRaw('DATE_FORMAT(' . $date_name . ', "%d") = "' . $this->hasParams('raw_day') . '"');
        }

        // fetch selected num
        if ($this->hasParams('limit')) {
            $query->limit($this->hasParams('limit'));
        }

        return $query;
    }

    /**
     * Get parameters
     *
     * @param $key
     * @return mixed|null
     */
    protected function hasParams($key)
    {
        return $this->params[$key] ?? null;
    }

    /**
     * Raw where for including and excluding
     *
     * @param $query
     * @return mixed
     */
    public function rawExcludeInclude($query)
    {
        $tableName = ($this->tableName) ? $this->tableName . '.' : '';
        $columns = $this->writableColumns;
        $columns[] = 'id';

        // ['column_name' => [value, value, value]]
        if ($this->hasParams('exclude')) {
            $exclude = $this->params['exclude'];
            $exclude = $this->extractIncExcData($exclude);

            foreach ($exclude as $key => $value) {
                if (in_array($key, $columns)) {
                    if (is_array($value)) {
                        $query->whereNotIn($tableName . $key, $value);
                    }
                }
            }
        }

        // ['column_name' => [value, value, value]]
        if ($this->hasParams('include')) {
            $include = $this->params['include'];
            $include = $this->extractIncExcData($include);

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
    protected function extractIncExcData($data)
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
    public function rawSearch($query)
    {
        if ($this->hasParams('search')) {
            // always check for values
            $query->where(function ($query) {
                $num = 0;
                // default columns table search
                foreach ($this->writableColumns as $row) {
                    $column_name = $this->tableName . '.' . $row;
                    $query = $this->searchQuery($num, $query, $column_name, $this->params['search']);
                    $num++;
                }

                // custom search parameters
                foreach ($this->rawQuerySelectList() as $column_name) {
                    $query = $this->searchQuery($num, $query, DB::raw('(' . $column_name . ')'), $this->params['search']);
                    $num++;
                }
            });
        } else if ($this->hasParams('search_only')) {
            // search only for specific columns
            $search_only = $this->params['search_only'];

            if (is_array($search_only)) {
                $query->where(function ($query) use ($search_only) {
                    // custom search parameters
                    $_customSearch = $this->rawQuerySelectList();

                    $num = 0;
                    foreach ($search_only as $column_name => $value) {
                        $column_name = $this->tableName . $column_name;
                        $query = $this->searchQuery($num, $query, $column_name, $value);
                        $num++;

                        if (isset($_customSearch[$column_name])) {
                            $query = $this->searchQuery($num, $query, DB::raw('(' . $_customSearch[$column_name] . ')'), $value);
                        }
                    }
                });
            }
        }

        $orderByColumn = $this->params['order_by_column'] ?? 'updated_at';
        $orderByDirection = $this->params['order_by_direction'] ?? 'DESC';
        $query->orderBy(DB::raw($orderByColumn), $orderByDirection);

        if ($this->hasParams('group_by')) {
            if (in_array($this->params['group_by'], $this->writableColumns)) {
                $query->groupBy($this->params['group_by']);
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
    protected function searchQuery($index, $query, $column_name, $search)
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
    public function rawFilters($query)
    {
        return $query;
    }

    /**
     * Ready for formatting data
     *
     * @param $query
     * @return mixed
     */
    protected function readyFormatting($query)
    {
        if ($this->hasParams('object')) {
            return $query;
        } else if ($this->hasParams('single')) {
            return $this->format($query->first());
        } else if ($this->hasParams('all')) {
            return $this->format($query->get());
        } else {
            $perPage = (int)__settings('pagination')->value;
            $perPage = ($perPage) ? $perPage : 12;
            return $this->format($query->paginate($perPage));
        }
    }

    /**
     * Add formatting on data
     *
     * @param $query
     * @return null
     */
    protected function format($query)
    {
        if ($this->hasParams('single')) {
            if (!$query) {
                return null;
            }

            $this->dataFormatting($query);
        } else {
            foreach ($query as $row) {
                $this->dataFormatting($row);
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
    protected function dataFormatting($row)
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
    public function store($inputs = [], $checkDefaults = true)
    {
        $store = [];
        foreach ($inputs as $key => $value) {
            if (in_array($key, $this->writableColumns) && $key !== 0 && $key !== null) {
                $value = ($value === '' || $value === null) ? null : $value;
                $store[$key] = $value;
            }
        }

        // add defaults
        if ($checkDefaults === true) {
            $store = $this->defaultInputs($store);
        }

        // other custom action
        if (!$this->actionStoreBefore($this->tableName, $inputs)) {
            return null;
        }

        // formatting before saving
        $store = $this->formatStore($this->tableName, $store);

        $store['created_at'] = sqlDate();
        $store['updated_at'] = sqlDate();
        $save = $this->single($this->insertGetId($store));

        // process files
        if ($save) {
            $this->_processStoreFiles($inputs, $save);
            $this->actionStoreAfter($save, $store);
        }

        // refresh data with files added
        if ($save && isset($save->_files)) {
            return $this->single($save->id);
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
    protected function defaultInputs($inputs = [], $defaults = [])
    {
        // clean dates
        foreach ($this->inputDates as $key) {
            if (isset($inputs[$key])) {
                if ($inputs[$key] !== null && $inputs[$key] !== '') {
                    $inputs[$key] = sqlDate($inputs[$key], true);
                } else {
                    unset($inputs[$key]);
                }
            }
        }

        // clean dates with time
        foreach ($this->inputDateTimes as $key) {
            if (isset($inputs[$key])) {
                if ($inputs[$key] !== null && $inputs[$key] !== '') {
                    $inputs[$key] = sqlDate($inputs[$key]);
                } else {
                    unset($inputs[$key]);
                }
            }
        }

        // clean integers with zero
        foreach ($this->inputIntegers as $key) {
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

        // clean numeric
        foreach ($this->inputNumeric as $key) {
            if (isset($inputs[$key])) {
                if ($inputs[$key] === null && $inputs[$key] === '' && !is_numeric($inputs[$key])) {
                    $inputs[$key] = 0;
                }
            } else {
                $inputs[$key] = 0;
            }
        }

        // clean boolean
        foreach ($this->inputBooleans as $key) {
            if (isset($inputs[$key])) {
                $inputs[$key] = ($inputs[$key] == 1) ? 1 : 0;
            } else {
                $inputs[$key] = 0;
            }
        }

        // BCrypt
        foreach ($this->inputCrypt as $key) {
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
     * Custom method for storing
     *
     * @param $tableName
     * @param $inputs
     * @return bool
     */
    public function actionStoreBefore($tableName, $inputs)
    {
        return true;
    }

    /**
     * Custom formatting for storing
     *
     * @param $tableName
     * @param $inputs
     * @return mixed
     */
    public function formatStore($tableName, $inputs)
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
    public function single($id, $column = 'id')
    {
        if (!$id) {
            return null;
        }

        return $this->fetch([
            'single' => true,
            $column => $id
        ]);
    }

    /**
     * After successful save
     *
     * @param $query
     * @param $inputs
     */
    public function actionStoreAfter($query, $inputs)
    {

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
    public function edit($id, $inputs = [], $columnName = null, $checkDefaults = true)
    {
        $update = [];
        $query = $this->rawFetch($id, $columnName);

        if (!$query) {
            return false;
        }

        if (!$query->first()) {
            return false;
        }

        // clean inputs
        foreach ($inputs as $key => $value) {
            if (in_array($key, $this->writableColumns) && $key !== 0 && $key !== null) {
                $value = ($value === '' || $value === null) ? null : $value;
                $update[$key] = $value;
            }
        }

        $_data = $query->first();

        // add defaults
        if ($checkDefaults === true) {
            $update = $this->defaultInputs($update, $_data);
        }

        // other custom action
        if (!$this->actionEditBefore($this->tableName, $_data, $inputs)) {
            return false;
        }

        // formatting before saving
        $update = $this->formatEdit($this->tableName, $update);

        $update['updated_at'] = sqlDate();
        $_u = (bool)$query->update($update);

        // save activity logs
        (new ActivityLog())->log($id, $this->tableName, $this->writableColumns, $_data, $update);

        // process files
        $this->processEditFiles($inputs, $update, $this->single($query->first()->id));

        return $_u;
    }

    /**
     * Raw fetch
     *
     * @param $id
     * @param null $columnName
     * @return bool|Builder|null
     */
    public function rawFetch($id, $columnName = null)
    {
        $query = null;

        if (!$columnName) {
            $columnName = 'id';
        }

        // filters for editing
        if ($id && !is_array($columnName)) {
            $query = DB::table($this->tableName)->where($columnName, $id);
        } else {
            $i = 0;
            foreach ($columnName as $key => $value) {
                if (!in_array($key, $this->writableColumns) && $key !== 'id') {
                    return false;
                }

                if (!$i) {
                    $query = DB::table($this->tableName)->where($key, $value);
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
     * Custom method for editing
     *
     * @param $tableName
     * @param $query
     * @param $inputs
     * @return bool
     */
    public function actionEditBefore($tableName, $query, $inputs)
    {
        return true;
    }

    /**
     * Custom formatting for editing
     *
     * @param $tableName
     * @param $inputs
     * @return mixed
     */
    public function formatEdit($tableName, $inputs)
    {
        return $inputs;
    }

    /**
     * Process files for edit
     *
     * @param $inputs
     * @param $update
     * @param $_data
     */
    public function processEditFiles($inputs, $update, $_data)
    {
        if ($_data) {
            $_data->_files = $this->_processFile($inputs, $_data);
            $this->actionEditAfter($_data, $update);
        }
    }

    /**
     * After updating the data
     *
     * @param $query
     * @param $inputs
     */
    public function actionEditAfter($query, $inputs)
    {

    }

    /**
     * Delete data
     *
     * @param $id
     * @param null $columnName
     *
     * @return integer
     */
    public function remove($id, $columnName = null)
    {
        // check if exists or tried to delete the users authorization
        $_r = $this->rawFetch($id, $columnName);
        if (!$_r) {
            return false;
        }

        if (!$this->actionRemoveBefore($_r->get())) {
            return false;
        }

        // remove
        $save = $this->rawFetch($id, $columnName)->delete();

        // delete and remove all files based on table name and id
        if ($save) {
            foreach ((new File())->fetch(['table_name' => $this->tableName, 'table_id' => $id]) as $file) {
                if (fileDestroy('private/' . $file->file_name)) {
                    (new File())->remove($file->id);
                }
            }
        }

        return $save;
    }

    /**
     * Before removing the data
     *
     * @param $query
     * @return bool
     */
    public function actionRemoveBefore($query)
    {
        return true;
    }

    /**
     * After removing the data
     *
     * @param $save
     */
    public function actionRemoveAfter($save)
    {

    }

    /**
     * Check if we allow null on filters
     *
     * @param $row
     * @return bool
     */
    private function _allowNull($row)
    {
        $_val = $this->params[$row] ?? null;
        $_allowNull = $this->params['allow_null'] ?? false;
        $_allowFilter = true;

        if ($_val === null && $_allowNull === false) {
            $_allowFilter = false;
        }

        return $_allowFilter;
    }

    /**
     * Check if we allow null on filters with defined value
     *
     * @param $val
     * @return bool
     */
    private function _allowNullWithVal($val)
    {
        $_allowNull = $this->params['allow_null'] ?? false;
        $_allowFilter = true;

        if ($val === null && $_allowNull === false) {
            $_allowFilter = false;
        }

        return $_allowFilter;
    }

    /**
     * Process file uploads from store
     *
     * @param $inputs
     * @param $save
     */
    private function _processStoreFiles($inputs, $save)
    {
        $save->_files = $this->_processFile($inputs, $save);
    }

    /**
     * Process files
     *
     * @param $inputs
     * @param $_data
     * @return array
     */
    private function _processFile($inputs, $_data)
    {
        $_files = [];

        foreach ($this->files as $file) {
            if (is_array($file)) {
                if (isset($inputs[$file['name']])) {
                    $_files[] = fileUpload([
                        'file' => $inputs[$file['name']],
                        'destination' => 'private',
                        'options' => $this->fileOptions,
                        'tableName' => $file['table'],
                        'tableId' => $_data->id,
                        'filename' => $file['name']
                    ]);
                }
            } else {
                if (isset($inputs[$file])) {
                    $_files[] = fileUpload([
                        'file' => $inputs[$file],
                        'destination' => 'private',
                        'options' => $this->fileOptions,
                        'tableName' => $this->tableName,
                        'tableId' => $_data->id,
                        'filename' => $file
                    ]);
                }
            }
        }

        return $_files;
    }
}