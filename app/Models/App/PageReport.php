<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models\App;

use App\Models\Vendor\BaseModel;
use App\Models\Vendor\Facades\File;
use App\Models\Vendor\Facades\User;
use \App\Models\Vendor\Facades\PageReportMessage;

class PageReport extends BaseModel
{
    protected $tableName = 'page_reports';
    protected $writableColumns = [
        'user_id', 'responded_by_id', 'page_report_reason_id',
        'url', 'description', 'status', 'rating'
    ];

    protected $columnHasRelations = ['user_id', 'responded_by_id', 'page_report_reason_id'];
    protected $columnWhere = ['status'];

    protected $files = ['screenshots'];

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

    protected function customQueries($query): void
    {
        $query->join('users', 'page_reports.user_id', '=', 'users.id');
        $query->join('page_report_reasons', 'page_reports.page_report_reason_id', '=', 'page_report_reasons.id');
    }

    protected function customQuerySelectList(): array
    {
        return [
            'submitted_by' => 'CONCAT(users.first_name, " ", users.last_name)',
            'submitted_by_gender' => 'users.gender',

            'process_by' => 'IF(page_reports.responded_by_id = 0, 
                "n/a", 
                (SELECT CONCAT(first_name, " ", last_name) AS name FROM users AS process 
                WHERE process.id = page_reports.responded_by_id))',
            'process_by_gender' => 'IF(page_reports.responded_by_id = 0, 
                "Male", (SELECT gender FROM users AS process WHERE process.id = page_reports.responded_by_id))',

            'page_report_reason_name' => 'page_report_reasons.name',

            'last_reply' => '(SELECT page_report_messages.message FROM page_report_messages WHERE 
                page_reports.id = page_report_messages.page_report_id 
                ORDER BY page_report_messages.created_at DESC LIMIT 1)'
        ];
    }

    protected function dataFormatting($row)
    {
        $this->addDateFormatting($row);

        $row->screenshots = File::fetchAll(['table_name' => 'page_reports', 'table_id' => $row->id]);
        $row->submitted_by_profile_picture = User::profilePicture($row->user_id, $row->submitted_by_gender);
        $row->process_by_profile_picture = User::profilePicture($row->responded_by_id, $row->process_by_gender);

        return $row;
    }

    public function actionRemoveBefore($results)
    {
        foreach ($results as $row) {
            PageReportMessage::remove(['page_report_id' => $row->id]);
            File::remove(['table_name' => 'page_reports', 'table_id' => $row->id]);
        }

        return true;
    }

    public function statuses()
    {
        return ['Pending', 'Processing', 'Reopened', 'Denied', 'Closed'];
    }
}
