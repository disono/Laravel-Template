<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models\Vendor;

use App\Models\Vendor\Facades\ActivityLog;
use App\Models\Vendor\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class BaseModel extends Model
{
    public $enableSearch = FALSE;
    protected $writableColumns = [];
    protected $tableName = NULL;

    protected $params = [];

    // single: [filename]
    // multiple: [[name => filename, table => table]]
    // File Options: [filename => [tag => string, crop_width => num, crop_height => num, crop_auto => boolean, width => num,
    // height => num, heightRatio => num, widthRatio => num, quality => int]]
    protected $files = [];
    protected $fileOptions = [];

    protected $inputCrypt = [];
    protected $inputBooleans = [];

    protected $inputDates = [];
    protected $inputDateTimes = [];

    protected $inputIntegers = [];
    protected $inputNumeric = [];

    protected $columnHasRelations = [];
    protected $columnWhere = [];
    protected $findInSetList = [];

    protected $hasTimestamp = TRUE;

    // do not sanitize HTML codes
    protected $unClean = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTableName());
    }

    public function getTableName()
    {
        return $this->getTable();
    }

    /**
     * Get THIS
     *
     * @return $this
     */
    public function self()
    {
        return $this;
    }

    /**
     * Set files
     *
     * @param $request
     * @param array $inputs
     */
    public function setFilesFromRequest($request, $inputs = []): void
    {
        foreach ($this->files as $file) {
            $inputs[$file] = $request->file($file);
        }
    }

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
     * Set writable column
     *
     * @param $value
     * @return BaseModel
     */
    public function setWritableColumn($value)
    {
        if (!$value) {
            return $this;
        }

        $this->writableColumns[] = $value;
        return $this;
    }

    /**
     * Remove all boolean to update
     */
    public function clearBoolean()
    {
        $this->inputBooleans = [];
        return $this;
    }

    /**
     * Remove all integer to update
     */
    public function clearInteger()
    {
        $this->inputIntegers = [];
        return $this;
    }

    /**
     * Remove all numeric to update
     */
    public function clearNumeric()
    {
        $this->inputNumeric = [];
        return $this;
    }

    /**
     * Get all data no pagination
     *
     * @param array $params
     * @return NULL
     */
    public function fetchAll($params = [])
    {
        $params['all'] = TRUE;
        $this->params = $params;
        return $this->fetch($params);
    }

    /**
     * Get data
     *
     * @param array $params
     * @return NULL
     */
    public function fetch($params = [])
    {
        $this->params = $params;
        $this->_cleanParams();
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

        // custom queries
        $this->customQueries($query);

        // add formatting
        return $this->_readyFormatting($query);
    }

    /**
     * Clean parameters
     */
    private function _cleanParams()
    {
        foreach ($this->params as $key => $value) {
            // do not clean files
            if (!in_array($key, $this->files)) {
                $this->params[$key] = !in_array($key, $this->unClean) ? dbCleanInput($value) : $value;
            }
        }
    }

    /**
     * Custom selects
     *
     * @param array $select
     * @return array
     */
    protected function rawSelects($select = []): array
    {
        foreach ($this->customQuerySelectList() as $key => $q) {
            $select[] = DB::raw('(' . $q . ') AS ' . $key);
        }

        return $select;
    }

    /**
     * List of select
     *
     * @return array
     */
    protected function customQuerySelectList(): array
    {
        return [];
    }

    /**
     * Raw where (writable columns equal, date range, current date)
     *
     * @param $query
     */
    protected function rawWhere($query): void
    {
        // do not continue if search is enabled
        if ($this->enableSearch === TRUE) {
            return;
        }

        // format table name
        $tableName = $this->tableName ? $this->tableName . '.' : '';

        // current id of the table index
        if (is_numeric($this->hasParams('id'))) {
            $query->where($tableName . 'id', $this->params['id']);
        }

        // equal fetch
        foreach ($this->writableColumns as $row) {
            // writable columns (non-custom)
            if (key_exists($row, $this->params) && !in_array($row, $this->findInSetList)) {
                if ($this->_allowNull($row) === TRUE) {
                    $query->where($tableName . $row, $this->params[$row]);
                }
            }

            // custom columns
            $defaultCustomColumn = $this->_fetchSelect($row);
            if ($this->_allowNullToVal($defaultCustomColumn) === TRUE) {
                $query->where(DB::raw($defaultCustomColumn), $this->params[$row]);
            }
        }

        // custom search
        foreach ($this->customQuerySelectList() as $key => $value) {
            if ($this->_allowNullToVal($value) === TRUE && $this->hasParams($key) !== NULL) {
                $query->where(DB::raw($value), $this->params[$key]);
            }
        }

        // FIND_IN_SET() Function
        if ($this->hasParams('find_in_set') === TRUE) {
            foreach ($this->findInSetList as $key) {
                if ($this->hasParams($key)) {
                    $query->whereRaw('FIND_IN_SET(?, ' . $tableName . $key . ')', [$this->hasParams($key)]);
                }
            }
        }

        // filter by dates
        $this->_filterByDates($query, $tableName);
    }

    /**
     * Get parameters
     *
     * @param $key
     * @return mixed|NULL
     */
    protected function hasParams($key)
    {
        return $this->params[$key] ?? NULL;
    }

    /**
     * Check if we allow NULL on filters
     *
     * @param $row
     * @return bool
     */
    private function _allowNull($row): bool
    {
        $_val = $this->params[$row] ?? NULL;
        $_allowNull = $this->params['allow_null'] ?? FALSE;

        if ($_val === NULL && $_allowNull === FALSE) {
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Custom queries
     *
     * @param $key
     * @return mixed|NULL
     */
    private function _fetchSelect($key = NULL)
    {
        $queries = $this->customQuerySelectList();

        if ($key === NULL) {
            return $queries;
        }

        return $queries[$key] ?? NULL;
    }

    /**
     * Check if we allow NULL on filters with defined value
     *
     * @param $val
     * @return bool
     */
    private function _allowNullToVal($val): bool
    {
        $_allowNull = $this->params['allow_null'] ?? FALSE;

        if ($val === NULL && $_allowNull === FALSE) {
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Filter by dates
     *
     * @param $query
     * @param $tableName
     */
    private function _filterByDates($query, $tableName)
    {
        // date range
        // date_range_from and date_range_to
        // custom date_range_name unless we use created_at
        // NOTE: https://www.w3schools.com/sql/func_mysql_date_format.asp
        if ($this->hasParams('date_range_from') && $this->hasParams('date_range_to')) {
            $date_name = $this->params['date_range_name'] ?? ($tableName . 'created_at');

            $query->whereBetween(DB::raw('DATE(' . $date_name . ')'), [
                sqlDate($this->params['date_range_from'], TRUE),
                sqlDate($this->params['date_range_to'], TRUE)
            ]);
        }

        // fetch today
        // if fetch_today is boolean get the data today unless specify the date
        if ($this->hasParams('fetch_today')) {
            $data_name_today = $this->params['fetch_today_name'] ?? ($tableName . 'created_at');
            $current_date = $this->params['fetch_today'] === TRUE || is_numeric($this->params['fetch_today']) ?
                'CURDATE()' :
                sqlDate($this->params['fetch_today'], TRUE);

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
    }

    /**
     * Raw where for including and excluding
     * values: ['column_name' => [value, value, value]]
     *
     * @param $query
     */
    protected function rawExcludeInclude($query): void
    {
        $tableName = $this->tableName ? $this->tableName . '.' : '';
        $columns = $this->writableColumns;
        $columns[] = 'id';

        if ($this->hasParams('exclude')) {
            foreach ($this->_extractIncExcData($this->params['exclude']) as $key => $value) {
                if (is_array($value) && in_array($key, $columns)) {
                    $query->whereNotIn($tableName . $key, $value);
                }
            }
        }

        if ($this->hasParams('include')) {
            foreach ($this->_extractIncExcData($this->params['include']) as $key => $value) {
                if (is_array($value) && in_array($key, $columns)) {
                    $query->whereIn($tableName . $key, $value);
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
    private function _extractIncExcData($data)
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
    protected function rawSearch($query): void
    {
        $num = 0;
        $tableName = $this->tableName ? $this->tableName . '.' : '';

        if ($this->hasParams('search')) {
            // always check for values
            $query->where(function ($query) use ($num, $tableName) {
                // default columns table search
                foreach ($this->writableColumns as $row) {
                    $column_name = $tableName . $row;
                    $this->_searchQuery($num, $query, $column_name, $this->params['search']);
                    $num++;
                }

                // custom search parameters
                foreach ($this->customQuerySelectList() as $column_name) {
                    $this->_searchQuery($num, $query, DB::raw('(' . $column_name . ')'), $this->params['search']);
                    $num++;
                }
            });
        } else if ($this->hasParams('search_column')) {
            // search only for specific columns
            $search_column = $this->params['search_column'];

            if (is_array($search_column)) {
                $query->where(function ($query) use ($num, $search_column, $tableName) {
                    // custom search parameters
                    $customSearch = $this->customQuerySelectList();

                    foreach ($search_column as $column_name => $value) {
                        $column = $tableName . $column_name;

                        // default columns
                        if (in_array($column_name, $this->writableColumns)) {
                            $this->_searchQuery($num, $query, $column, $value);
                            $num++;
                        }

                        // customer search
                        if (isset($customSearch[$column])) {
                            $this->_searchQuery($num, $query, DB::raw('(' . $customSearch[$column] . ')'), $value);
                        }
                    }
                });
            }
        } else if ($this->enableSearch === TRUE) {
            $query->where(function ($query) use ($tableName) {
                // default columns table search
                foreach ($this->writableColumns as $row) {
                    if (key_exists($row, $this->params) && !in_array($row, $this->findInSetList)) {
                        if ($this->params[$row] !== NULL && $this->params[$row] !== '') {
                            $column_name = $tableName . $row;

                            if (in_array($row, $this->inputBooleans) || in_array($row, $this->columnHasRelations) || in_array($row, $this->columnWhere)) {
                                // do not use LIKE search for booleans and join data
                                $query->where($column_name, $this->params[$row]);
                            } else if (in_array($row, $this->inputDateTimes) || in_array($row, $this->inputDates) ||
                                $row === 'created_at' || $row === 'updated_at') {
                                $this->_searchQuery(0, $query, 'DATE(' . $column_name . ')', sqlDate($this->params[$row], TRUE));
                            } else {
                                $this->_searchQuery(0, $query, $column_name, $this->params[$row]);
                            }
                        }
                    }
                }

                // custom search parameters
                foreach ($this->customQuerySelectList() as $key => $column_name) {
                    if (key_exists($key, $this->params)) {
                        if ($this->params[$key] !== NULL && $this->params[$key] !== '') {
                            $this->_searchQuery(0, $query, DB::raw('(' . $column_name . ')'), $this->params[$key]);
                        }
                    }
                }

                // FIND_IN_SET() Function
                if ($this->hasParams('find_in_set') === TRUE) {
                    foreach ($this->findInSetList as $key) {
                        if ($this->hasParams($key)) {
                            $query->whereRaw('FIND_IN_SET(?, ' . $tableName . $key . ')', [$this->hasParams($key)]);
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
    private function _searchQuery($index, $query, $column_name, $search): void
    {
        // check values to search must not be empty
        if ($search != '' && $search != NULL) {
            if (!$index || $index === 0) {
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
    protected function rawOrdering($query): void
    {
        // ordering
        if ($this->hasTimestamp) {
            $orderByColumn = $this->params['order_by_column'] ?? 'updated_at';
            $orderByDirection = $this->params['order_by_direction'] ?? 'DESC';
            $query->orderBy(DB::raw($orderByColumn), $orderByDirection);
        }

        // latest
        if ($this->hasParams('order_latest')) {
            $query->latest();
        }

        // oldest
        if ($this->hasParams('order_oldest')) {
            $query->oldest();
        }

        // random
        if ($this->hasParams('random_query')) {
            $query->inRandomOrder();
        }

        // grouping
        if ($this->hasParams('group_by')) {
            if (in_array($this->params['group_by'], $this->writableColumns)) {
                $query->groupBy($this->params['group_by']);
            }
        }

        // take
        if ($this->hasParams('take_query')) {
            $query->take($this->hasParams('take_query'));
        }

        // offset
        if ($this->hasParams('offset_query')) {
            $query->offset($this->hasParams('offset_query'));
        }

        // limit
        if ($this->hasParams('limit_query')) {
            $query->limit($this->hasParams('limit_query'));
        }
    }

    /**
     * Custom filters
     *
     * @param $query
     */
    protected function customQueries($query): void
    {

    }

    /**
     * Ready for formatting data
     *
     * @param $query
     * @return mixed
     */
    private function _readyFormatting($query)
    {
        if ($this->hasParams('object')) {
            return $query;
        } else if ($this->hasParams('single')) {
            return $this->_formatting($query->first());
        } else if ($this->hasParams('all')) {
            return $this->_formatting($query->get());
        } else {
            $count = $query->count();

            if ($this->hasParams('pagination_show')) {
                $perPage = (int)$this->hasParams('pagination_show');
            } else {
                $perPage = (int)__settings('pagination')->value;
                $perPage = ($perPage) ? $perPage : 12;
            }

            return $this->_formatting($query->paginate($perPage), $count);
        }
    }

    /**
     * Add formatting on data
     *
     * @param $query
     * @param int $count
     * @return NULL
     */
    private function _formatting($query, $count = 0)
    {
        if ($this->hasParams('single')) {
            if (!$query) {
                return NULL;
            }

            $this->dataFormatting($query);
        } else {
            foreach ($query as $row) {
                $this->dataFormatting($row);
            }

            $query->total_records = $count;
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
        $this->addDateFormatting($row);
        return $row;
    }

    /**
     * Add date formatting for human readable
     *
     * @param $row
     */
    protected function addDateFormatting($row)
    {
        foreach ($this->inputDates as $date) {
            $rowDate = $row->$date ?? NULL;
            $dateName = 'formatted_' . $date;
            if ($rowDate) {
                $row->{$dateName} = humanDate($row->$date, TRUE);
            } else {
                $row->{$dateName} = NULL;
            }
        }

        foreach ($this->inputDateTimes as $dateTime) {
            $rowDate = $row->$dateTime ?? NULL;
            $dateName = 'formatted_' . $dateTime;
            if ($rowDate) {
                $row->{$dateName} = humanDate($row->$dateTime);
            } else {
                $row->{$dateName} = NULL;
            }
        }

        $createdAt = $row->created_at ?? NULL;
        if ($createdAt) {
            $row->formatted_created_at = humanDate($createdAt);
        }

        $updatedAt = $row->updated_at ?? NULL;
        if ($updatedAt) {
            $row->formatted_updated_at = humanDate($updatedAt);
        }
    }

    /**
     * Store new data
     *
     * @param array $inputs
     * @param bool $checkDefaults
     * @return bool
     */
    public function store($inputs = [], $checkDefaults = TRUE)
    {
        // clean inputs
        $store = $this->_cleanInputs($inputs);

        // add defaults
        if ($checkDefaults === TRUE) {
            $store = $this->_defaultInputs($store);
        }

        // other custom action
        if (!$this->actionStoreBefore($this->tableName, $inputs)) {
            return NULL;
        }

        // formatting before saving
        $store = $this->formatStore($this->tableName, $store);

        // add dates
        $store['created_at'] = sqlDate();
        $store['updated_at'] = sqlDate();
        $save = $this->single($this->insertGetId($store));

        if ($save) {
            // process files
            $this->_processStoreFiles($inputs, $save);
            $this->actionStoreAfter($save, $store);

            // refresh data with files added
            if (isset($save->_files)) {
                return $this->single($save->id);
            }
        }

        return $save;
    }

    /**
     * Clean inputs
     *
     * @param $inputs
     *
     * @return array
     */
    private function _cleanInputs($inputs)
    {
        $data = [];

        foreach ($inputs as $key => $value) {
            if (in_array($key, $this->writableColumns) && $key !== 0 && $key !== NULL) {
                $value = ($value === '' || $value === NULL) ? NULL : $value;
                $data[$key] = !in_array($key, $this->unClean) ? dbCleanInput($value) : $value;
            }
        }

        return $data;
    }

    /**
     * Default inputs
     *
     * @param array $inputs
     * @return array
     */
    private function _defaultInputs($inputs = [])
    {
        // clean dates
        foreach ($this->inputDates as $key) {
            $_date = $inputs[$key] ?? NULL;
            if ($_date) {
                if ($_date !== NULL && $_date !== '') {
                    $inputs[$key] = sqlDate($_date, TRUE);
                } else {
                    unset($inputs[$key]);
                }
            }
        }

        // clean dates with time
        foreach ($this->inputDateTimes as $key) {
            $_dateTimes = $inputs[$key] ?? NULL;
            if ($_dateTimes) {
                if ($_dateTimes !== NULL && $_dateTimes !== '') {
                    $inputs[$key] = sqlDate($_dateTimes);
                } else {
                    unset($inputs[$key]);
                }
            }
        }

        // clean integers with zero
        foreach ($this->inputIntegers as $key) {
            $_integers = $inputs[$key] ?? NULL;
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
            $_num = $inputs[$key] ?? NULL;
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
            $_bool = $inputs[$key] ?? NULL;
            if ($_bool) {
                $inputs[$key] = ($_bool == 1) ? 1 : 0;
            } else {
                $inputs[$key] = 0;
            }
        }

        // BCrypt
        foreach ($this->inputCrypt as $key) {
            $_crypt = $inputs[$key] ?? NULL;
            if ($_crypt) {
                if ($_crypt !== NULL && $_crypt !== '') {
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
        return TRUE;
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
     * @return NULL
     */
    public function single($id, $column = 'id')
    {
        if (!$id) {
            return NULL;
        }

        if (is_array($id)) {
            $inputs = array_merge($id, [
                'single' => TRUE
            ]);
        } else {
            $inputs = [
                'single' => TRUE,
                $column => $id
            ];
        }

        return $this->fetch($inputs);
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
                    $_files[] = $this->_processUploadedFile($inputs[$file['name']], $file['name'], $file['table'], $_data->id);
                }
            } else {
                if (isset($inputs[$file])) {
                    $_files[] = $this->_processUploadedFile($inputs[$file], $file, $this->tableName, $_data->id);
                }
            }
        }

        return $_files;
    }

    /**
     * Process upload file
     *
     * @param $file
     * @param $fileName
     * @param $tableName
     * @param $tableId
     * @param string $destination
     * @return array
     */
    private function _processUploadedFile($file, $fileName, $tableName, $tableId, $destination = 'private')
    {
        return fileUpload([
            'file' => $file,
            'destination' => $destination,
            'options' => $this->fileOptions,
            'tableName' => $tableName,
            'tableId' => $tableId,
            'filename' => $fileName
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
     * @param bool $checkDefaults
     * @param bool $log
     * @return bool
     */
    public function edit($id, $inputs = [], $checkDefaults = TRUE, $log = TRUE)
    {
        // clean inputs
        $update = $this->_cleanInputs($inputs);

        $query = $this->rawFetch($id);
        if (!$query) {
            return FALSE;
        }

        if (!$query->first()) {
            return FALSE;
        }

        $_data = $query->first();

        // add default values
        if ($checkDefaults === TRUE) {
            $update = $this->_defaultInputs($update);
        }

        // before updating
        if (!$this->actionEditBefore($this->tableName, $_data, $inputs)) {
            return FALSE;
        }

        // formatting before saving
        $update = $this->formatEdit($this->tableName, $update);

        // update
        $update['updated_at'] = sqlDate();
        $results = (bool)$query->update($update);

        // save activity logs
        if ($log) {
            ActivityLog::log($id, $this->tableName, $this->writableColumns, $_data, $update);
        }

        // process files
        $this->_processEditFiles($inputs, $update, $this->single($query->first()->id));

        return $results;
    }

    /**
     * Raw fetch
     *
     * @param $id
     * @param NULL $columnName
     * @return bool|Builder|NULL
     */
    protected function rawFetch($id, $columnName = NULL)
    {
        $query = NULL;

        if (!$columnName) {
            $columnName = 'id';
        }

        // filters for editing
        if (!is_array($id) && is_string($columnName)) {
            $query = DB::table($this->tableName)->where($columnName, $id);
        } else if (is_array($id)) {
            $i = 0;
            foreach ($id as $key => $value) {
                if (!in_array($key, $this->writableColumns) && $key !== 'id') {
                    return FALSE;
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
    protected function actionEditBefore($tableName, $query, $inputs)
    {
        return TRUE;
    }

    /**
     * Custom formatting for editing
     *
     * @param $tableName
     * @param $inputs
     * @return mixed
     */
    protected function formatEdit($tableName, $inputs)
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
    protected function _processEditFiles($inputs, $update, $_data)
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
     * integer or array
     *
     * @param NULL $columnName
     * @return boolean
     */
    public function remove($id, $columnName = NULL)
    {
        // check if exists or tried to delete the users authorization
        $query = $this->rawFetch($id, $columnName);
        if (!$query) {
            return FALSE;
        }

        // before removing
        if (!$this->actionRemoveBefore($query->get())) {
            return FALSE;
        }

        // remove in db
        $save = $query->delete();

        // remove related files
        $this->_actionRemoveFiles($id, $save);

        // after removing
        $this->actionRemoveAfter($save);
        return $save;
    }

    /**
     * Before removing the data
     *
     * @param $results
     * @return bool
     */
    protected function actionRemoveBefore($results)
    {
        return TRUE;
    }

    /**
     * Remove or delete all related files
     *
     * @param $id
     * @param $save
     */
    private function _actionRemoveFiles($id, $save)
    {
        // delete and remove all files based on table name and id
        if ($save) {
            foreach (File::fetch(['table_name' => $this->tableName, 'table_id' => $id]) as $file) {
                if (fileDestroy('private/' . $file->file_name)) {
                    File::remove($file->id);
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

}