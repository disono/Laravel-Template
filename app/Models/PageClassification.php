<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models;

use App\Models\Vendor\BaseModel;

class PageClassification extends BaseModel
{
    protected $hasTimestamp = false;

    protected $tableName = 'page_classifications';
    protected $writableColumns = [
        'page_id', 'page_category_id'
    ];

    protected $columnHasRelations = ['page_id', 'page_category_id'];

    public function __construct(array $attributes = [])
    {
        $this->fillable($this->writableColumns);
        parent::__construct($attributes);
    }

    public function page()
    {
        return $this->belongsTo('App\Models\Page');
    }

    public function pageCategory()
    {
        return $this->belongsTo('App\Models\PageCategory');
    }

    protected function customQueries($query): void
    {
        $query->join('pages', 'page_classifications.page_id', '=', 'pages.id');
        $query->join('page_categories', 'page_classifications.page_category_id', '=', 'page_categories.id');
    }

    protected function customQuerySelectList(): array
    {
        return [
            'page_name' => 'pages.name',
            'category_name' => 'page_categories.name'
        ];
    }
}
