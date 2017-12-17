<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models;

use Illuminate\Support\Facades\DB;

class ActivityLog extends AppModel
{
    protected static $table_name = 'activity_logs';
    private static $full_name;
    private static $username;

    protected static $writable_columns = [
        'user_id',
        'source_id', 'source_type',
        'content', 'reason'
    ];

    public function __construct(array $attributes = [])
    {
        $this->fillable(self::$writable_columns);
        parent::__construct($attributes);
    }

    /**
     * List data
     *
     * @param $source_id
     * @param $source_type
     * @param array $params
     * @return \Illuminate\Pagination\LengthAwarePaginator|null
     */
    public static function logs($source_id, $source_type, $params = [])
    {
        $table_name = (new self)->getTable();
        $select[] = $table_name . '.*';

        self::$full_name = 'CONCAT(first_name, " ", last_name)';
        $select[] = DB::raw(self::$full_name . ' as full_name');

        self::$username = '(' . self::_username($table_name) . ')';
        $select[] = DB::raw(self::$username . ' as username');

        $query = self::select($select)
            ->join('users', $table_name . '.user_id', '=', 'users.id');

        // where equal
        $query = self::_whereEqual($query, $params, self::$writable_columns, $table_name);

        // exclude and include
        $query = self::_excInc($query, $params, self::$writable_columns, $table_name);

        // search
        $query = self::_search($query, $params, self::$writable_columns, $table_name, [
            DB::raw(self::$username), DB::raw(self::$full_name)
        ]);

        if ($source_id) {
            $query->where($table_name . '.source_id', $source_id);
        }

        if ($source_type) {
            $query->where($table_name . '.source_type', $source_type);
        }

        $query->orderBy($table_name . '.created_at', 'DESC');

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
     * Return all available source types
     * check the config/database.php
     * then must be 'strict' => false
     *
     * @return mixed
     */
    public static function source_type()
    {
        $table_name = (new self)->getTable();
        $sources = DB::table($table_name)->groupBy($table_name . '.source_type')->get();

        $clean = [];
        if (count($sources)) {
            foreach ($sources as $row) {
                $clean[] = $row->source_type;
            }
        }

        return $clean;
    }

    /**
     * Username query string
     *
     * @param $table_name
     * @return string
     */
    private static function _username($table_name)
    {
        return 'SELECT name FROM slugs WHERE source_id = ' . $table_name . '.user_id AND source_type = "user"';
    }

    /**
     * Add formatting to data
     *
     * @param $row
     * @return mixed
     */
    public static function _dataFormatting($row)
    {
        if ($row->content) {
            $row->content = json_decode($row->content, true);
        } else {
            $row->content = [];
        }

        $row->formatted_created_at = human_date($row->created_at);

        return $row;
    }

    /**
     * Store activity mostly used for updates on database
     *
     * $id: Table id
     * $writable_columns: This where the models writable columns
     * $data: Current table data
     * $inputs: The current update data
     * $source_type: Mostly the table name
     * $reason: Reason for updates
     *
     * @param $id
     * @param array $writable_columns
     * @param array $data
     * @param array $inputs
     * @param $source_type
     * @param null $reason
     * @return bool
     */
    public static function log($id, $writable_columns = [], $data = [], $inputs = [], $source_type, $reason = null)
    {
        try {
            if (!$data) {
                return false;
            }

            $clean = [];
            $me = me();

            if (!$me) {
                return false;
            }

            foreach ($writable_columns as $column) {
                if (isset($data->$column) && isset($inputs[$column])) {

                    if ($data->$column != $inputs[$column]) {
                        $input_data = $data->$column;

                        // check if data new data is date
                        if ((bool)strtotime($inputs[$column]) && (bool)strtotime($data->$column)) {
                            if (human_date($inputs[$column]) != human_date($data->$column)) {
                                $clean[$column] = $input_data;
                            }
                        } else {
                            $clean[$column] = $input_data;
                        }
                    }
                }
            }

            if (!count($clean)) {
                return false;
            }

            $clean = json_encode($clean);
            $activity_log_reason = (isset($inputs['activity_log_reason'])) ? $inputs['activity_log_reason'] : $reason;
            return (boolean)self::insert([
                'user_id' => $me->id,

                'source_id' => $id,
                'source_type' => $source_type,

                'content' => $clean,
                'reason' => $activity_log_reason,

                'created_at' => sql_date()
            ]);
        } catch (\Exception $e) {
            error_logger('Activity Log Failed: ' . $e->getMessage());
            return false;
        }
    }
}
