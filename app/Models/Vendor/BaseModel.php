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
    public $enableSearch = false;
    protected $writableColumns = [];
    protected $tableName = null;

    // single: [filename]
    // multiple: [[filename, table]]
    protected $params = [];
    // [filename => [tag => string, crop_width => num, crop_height => num, crop_auto => boolean, width => num,
    // height => num, heightRatio => num, widthRatio => num, quality => int]]
    protected $files = [];
    protected $fileOptions = [];
    protected $inputDates = [];
    protected $inputDateTimes = [];
    protected $inputCrypt = [];
    protected $inputIntegers = [];
    protected $inputNumeric = [];
    protected $inputBooleans = [];
    protected $columnHasRelations = [];
    protected $columnWhere = [];

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
     * Set a new writable column
     *
     * @param $value
     */
    public function setNewWritableColumn($value): void
    {
        if (!$value) {
            return;
        }

        $this->writableColumns[] = $value;
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

        // custom select
        $query = DB::table($this->tableName)->select($this->rawSelects($select));

        // where equal
        $this->rawWhere($query);

        // exclude and include
        $this->rawExcludeInclude($query);

        // search
        $this->rawSearch($query);

        // ordering
        $this->rawOrdering($query);

        // custom filter
        $this->rawFilters($query);

        // add formatting
        return $this->readyFormatting($query);
    }

    /**
     * Custom selects
     *
     * @param array $select
     * @return array
     */
    protected function rawSelects($select = [])
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
     */
    protected function rawWhere($query, $customColumns = []): void
    {
        if ($this->enableSearch === true) {
            return;
        }

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
            if ($this->_allowNullWithVal($defaultCustomColumn) === true) {
                $query->where(DB::raw($defaultCustomColumn), $this->params[$row]);
            }
        }

        // custom search
        foreach ($this->rawQuerySelectList() as $key => $value) {
            if ($this->_allowNullWithVal($value) === true && $this->hasParams($key) !== null) {
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
        // if fetch_today is boolean get the data today unless specify the date
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
     * Check if we allow null on filters
     *
     * @param $row
     * @return bool
     */
    private function _allowNull($row): bool
    {
        $_val = $this->params[$row] ?? null;
        $_allowNull = $this->params['allow_null'] ?? false;

        if ($_val === null && $_allowNull === false) {
            return false;
        }

        return true;
    }

    /**
     * Check if we allow null on filters with defined value
     *
     * @param $val
     * @return bool
     */
    private function _allowNullWithVal($val): bool
    {
        $_allowNull = $this->params['allow_null'] ?? false;

        if ($val === null && $_allowNull === false) {
            return false;
        }

        return true;
    }

    /**
     * Raw where for including and excluding
     *
     * @param $query
     */
    public function rawExcludeInclude($query): void
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
     */
    public function rawSearch($query): void
    {
        $num = 0;

        if ($this->hasParams('search')) {
            // always check for values
            $query->where(function ($query) use ($num) {
                // default columns table search
                foreach ($this->writableColumns as $row) {
                    $column_name = $this->tableName . '.' . $row;
                    $this->searchQuery($num, $query, $column_name, $this->params['search']);
                    $num++;
                }

                // custom search parameters
                foreach ($this->rawQuerySelectList() as $column_name) {
                    $this->searchQuery($num, $query, DB::raw('(' . $column_name . ')'), $this->params['search']);
                    $num++;
                }
            });
        } else if ($this->hasParams('search_only')) {
            // search only for specific columns
            $search_only = $this->params['search_only'];

            if (is_array($search_only)) {
                $query->where(function ($query) use ($num, $search_only) {
                    // custom search parameters
                    $_customSearch = $this->rawQuerySelectList();
                    foreach ($search_only as $column_name => $value) {
                        $column_name = $this->tableName . $column_name;
                        $this->searchQuery($num, $query, $column_name, $value);
                        $num++;

                        // customer search
                        if (isset($_customSearch[$column_name])) {
                            $this->searchQuery($num, $query, DB::raw('(' . $_customSearch[$column_name] . ')'), $value);
                        }
                    }
                });
            }
        } else if ($this->enableSearch === true) {
            $query->where(function ($query) use ($num) {

                // default columns table search
                foreach ($this->writableColumns as $row) {
                    if (key_exists($row, $this->params)) {
                        if ($this->params[$row] !== null && $this->params[$row] !== '') {
                            $column_name = $this->tableName . '.' . $row;

                            if (in_array($row, $this->inputBooleans) || in_array($row, $this->columnHasRelations) || in_array($row, $this->columnWhere)) {

                                // do not use LIKE search for booleans and join data
                                $query->where($column_name, $this->params[$row]);
                            } else if (in_array($row, $this->inputDateTimes) || in_array($row, $this->inputDates) ||
                                $row === 'created_at' || $row === 'updated_at') {

                                $this->searchQuery($num, $query, 'DATE(' . $column_name . ')', sqlDate($this->params[$row], true));
                            } else {

                                $this->searchQuery($num, $query, $column_name, $this->params[$row]);
                            }

                            $num++;
                        }
                    }
                }

                // custom search parameters
                foreach ($this->rawQuerySelectList() as $key => $column_name) {
                    if (key_exists($key, $this->params)) {

                        if ($this->params[$key] !== null && $this->params[$key] !== '') {

                            $this->searchQuery($num, $query, DB::raw('(' . $column_name . ')'), $this->params[$key]);
                            $num++;
                        }
                    }
                }
            });
        }
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
    protected function searchQuery($index, $query, $column_name, $search): void
    {
        // check values to search must not be empty
        if ($search != '' && $search != null) {
            if (!$index) {
                $query->where(DB::RAW($column_name), 'LIKE', '%' . $search . '%');
            } else {
                $query->orWhere(DB::RAW($column_name), 'LIKE', '%' . $search . '%');
            }
        }
    }

    /**
     * Ordering
     *
     * @param $query
     */
    public function rawOrdering($query): void
    {
        // ordering
        $orderByColumn = $this->params['order_by_column'] ?? 'updated_at';
        $orderByDirection = $this->params['order_by_direction'] ?? 'DESC';
        $query->orderBy(DB::raw($orderByColumn), $orderByDirection);

        // grouping
        if ($this->hasParams('group_by')) {
            if (in_array($this->params['group_by'], $this->writableColumns)) {
                $query->groupBy($this->params['group_by']);
            }
        }
    }

    /**
     * Custom filters
     *
     * @param $query
     */
    public function rawFilters($query): void
    {

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
            if ($this->hasParams('pagination_show')) {
                $perPage = (int)$this->hasParams('pagination_show');
            } else {
                $perPage = (int)__settings('pagination')->value;
                $perPage = ($perPage) ? $perPage : 12;
            }

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

            $query->total_records = $query->count();
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
            $store = $this->_defaultInputs($store);
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
     * @return array
     */
    protected function _defaultInputs($inputs = [])
    {
        // clean dates
        foreach ($this->inputDates as $key) {
            $_date = $inputs[$key] ?? null;
            if ($_date) {
                if ($_date !== null && $_date !== '') {
                    $inputs[$key] = sqlDate($_date, true);
                } else {
                    unset($inputs[$key]);
                }
            }
        }

        // clean dates with time
        foreach ($this->inputDateTimes as $key) {
            $_dateTimes = $inputs[$key] ?? null;
            if ($_dateTimes) {
                if ($_dateTimes !== null && $_dateTimes !== '') {
                    $inputs[$key] = sqlDate($_dateTimes);
                } else {
                    unset($inputs[$key]);
                }
            }
        }

        // clean integers with zero
        foreach ($this->inputIntegers as $key) {
            $_integers = $inputs[$key] ?? null;
            if ($_integers) {
                if ($_integers !== '' && is_numeric($_integers)) {
                    $inputs[$key] = (int)$_integers;
                } else {
                    $inputs[$key] = 0;
                }
            } else {
                $inputs[$key] = 0;
            }
        }

        // clean numeric
        foreach ($this->inputNumeric as $key) {
            $_num = $inputs[$key] ?? null;
            if ($_num) {
                if ($_num === '' || !is_numeric($_num)) {
                    $inputs[$key] = 0;
                }
            } else {
                $inputs[$key] = 0;
            }
        }

        // clean boolean
        foreach ($this->inputBooleans as $key) {
            $_bool = $inputs[$key] ?? null;
            if ($_bool) {
                $inputs[$key] = ($_bool == 1) ? 1 : 0;
            } else {
                $inputs[$key] = 0;
            }
        }

        // BCrypt
        foreach ($this->inputCrypt as $key) {
            $_crypt = $inputs[$key] ?? null;
            if ($_crypt) {
                if ($_crypt !== null && $_crypt !== '') {
                    $inputs[$key] = bcrypt($_crypt);
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
            $update = $this->_defaultInputs($update);
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
    protected function actionEditAfter($query, $inputs)
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

        // remove related files
        $this->actionRemoveFiles($id, $save);

        $this->actionRemoveAfter($save);
        return $save;
    }

    /**
     * Before removing the data
     *
     * @param $query
     * @return bool
     */
    protected function actionRemoveBefore($query)
    {
        return true;
    }

    /**
     * Remove or delete all related files
     *
     * @param $id
     * @param $save
     */
    protected function actionRemoveFiles($id, $save)
    {
        // delete and remove all files based on table name and id
        if ($save) {
            foreach ((new File())->fetch(['table_name' => $this->tableName, 'table_id' => $id]) as $file) {
                if (fileDestroy('private/' . $file->file_name)) {
                    (new File())->remove($file->id);
                }
            }
        }
    }

    /**
     * After removing the data
     *
     * @param $save
     */
    protected function actionRemoveAfter($save)
    {

    }

    /**
     * Is input has value
     *
     * @param $key
     * @return bool
     */
    protected function hasValue($key)
    {
        $_param = $this->hasParams($key);
        if (!$_param || $_param === null || $_param === '') {
            return false;
        }

        return true;
    }
}