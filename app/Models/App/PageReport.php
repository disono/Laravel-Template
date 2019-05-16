<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models\App;

use App\Models\File;
use App\Models\User;
use App\Models\Vendor\BaseModel;

class PageReport extends BaseModel
{
    protected $tableName = 'page_reports';
    protected $writableColumns = [
        'user_id', 'responded_by', 'page_report_reason_id',
        'url', 'description', 'status', 'rating'
    ];

    protected static $files = ['screenshots'];

    public function __construct(array $attributes = [])
    {
        $this->fillable($this->writableColumns);
        parent::__construct($attributes);
    }

    public function pageReportReason()
    {
        return $this->belongsTo('App\Models\App\ReportPage');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * List of select
     *
     * @return array
     */
    protected function rawQuerySelectList()
    {
        return [
            'submitted_by' => 'CONCAT(users.first_name, " ", users.last_name)',
            'page_report_reason_name' => 'page_report_reasons.name',
        ];
    }

    public function rawFilters($query)
    {
        $query->join('users', 'page_reports.user_id', '=', 'users.id');
        $query->join('page_report_reasons', 'page_reports.page_report_reason_id', '=', 'page_report_reasons.id');
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
        $row->user = (new User())->single($row->user_id);
        $row->process_by = (new User())->single($row->responded_by);
        $row->reason = (new PageReportReason())->single($row->page_report_reason_id);
        $row->screenshots = (new File())->fetchAll(['table_name' => 'page_reports', 'table_id' => $row->id]);

        return $row;
    }

    public function statuses()
    {
        return ['Pending', 'Processing', 'Reopened', 'Denied', 'Closed'];
    }
}
