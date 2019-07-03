<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models;

use App\Models\Vendor\BaseModel;

class ActivityLog extends BaseModel
{
    protected $tableName = 'activity_logs';
    protected $writableColumns = [
        'user_id', 'table_id', 'table_name',
        'content', 'reason'
    ];

    protected $columnHasRelations = ['user_id', 'table_id'];

    public function __construct(array $attributes = [])
    {
        $this->fillable($this->writableColumns);
        parent::__construct($attributes);
    }

    protected function customQueries($query): void
    {
        $query->join('users', 'activity_logs.user_id', '=', 'users.id');
    }

    protected function customQuerySelectList(): array
    {
        return [
            'full_name' => 'CONCAT(users.first_name, " ", users.last_name)',
        ];
    }

    protected function dataFormatting($row)
    {
        $this->addDateFormatting($row);

        if ($row->content) {
            $row->content = json_decode($row->content, true);
        } else {
            $row->content = [];
        }

        return $row;
    }

    /**
     * Store activity mostly used for updates on database
     *
     * $id: Table id
     * $writableColumns: This where the models writable columns
     * $data: Current table data
     * $inputs: The current update data
     * $tableName: Mostly the table name
     * $reason: Reason for updates
     *
     * @param $id
     * @param $tableName
     * @param array $writableColumns
     * @param array $data
     * @param array $inputs
     * @param null $reason
     * @return bool
     */
    public function log($id, $tableName, $writableColumns = [], $data = [], $inputs = [], $reason = null)
    {
        try {
            if (!$data) {
                return false;
            }

            $clean = [];
            $me = __me();

            if (!$me) {
                return false;
            }

            foreach ($writableColumns as $column) {
                if (isset($data->$column) && isset($inputs[$column])) {

                    if ($data->$column != $inputs[$column]) {
                        $input_data = $data->$column;

                        // check if data new data is date
                        if ((bool)strtotime($inputs[$column]) && (bool)strtotime($data->$column)) {
                            if (humanDate($inputs[$column]) !== humanDate($data->$column)) {
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
            $activity_log_reason = $inputs['activity_log_reason'] ?? $reason;
            return (boolean)self::insert([
                'user_id' => $me->id,

                'table_id' => $id,
                'table_name' => $tableName,

                'content' => $clean,
                'reason' => $activity_log_reason,

                'created_at' => sqlDate()
            ]);
        } catch (\Exception $e) {
            logErrors('Activity Log Failed: ' . $e->getMessage());
            return false;
        }
    }
}
