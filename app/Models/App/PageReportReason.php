<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models\App;

use App\Models\Vendor\BaseModel;

class PageReportReason extends BaseModel
{
    protected static $tableName = 'page_report_reasons';
    protected static $writableColumns = [
        'name'
    ];

    public function __construct(array $attributes = [])
    {
        $this->fillable(self::$writableColumns);
        parent::__construct($attributes);
    }

    public static function actionRemoveBefore($query)
    {
        foreach ($query as $row) {
            // is reason used by reported pages
            if (PageReport::where('page_report_reason_id', $row->id)->first()) {
                return false;
            }
        }

        return true;
    }
}
