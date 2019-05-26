<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models\App;

use App\Models\User;
use App\Models\Vendor\BaseModel;

class PageReportMessage extends BaseModel
{
    protected $tableName = 'page_report_messages';
    protected $writableColumns = [
        'page_report_id', 'user_id', 'message'
    ];

    protected $columnHasRelations = ['page_report_id', 'user_id'];

    public function __construct(array $attributes = [])
    {
        $this->fillable($this->writableColumns);
        parent::__construct($attributes);
    }

    public function reportPage()
    {
        return $this->belongsTo('App\Models\App\ReportPage');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function rawFilters($query): void
    {
        $query->join('users', 'page_report_messages.user_id', '=', 'users.id');
    }

    protected function dataFormatting($row)
    {
        $row->user = (new User())->single($row->user_id);

        return $row;
    }
}
