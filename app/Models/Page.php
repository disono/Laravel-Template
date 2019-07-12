<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Models;

use App\Models\Vendor\BaseModel;
use App\Models\Vendor\Facades\File;
use App\Models\Vendor\Facades\PageCategory;
use App\Models\Vendor\Facades\PageClassification;
use App\Models\Vendor\Facades\PageView;

class Page extends BaseModel
{
    protected $tableName = 'pages';
    protected $writableColumns = [
        'user_id',
        'name', 'content', 'summary', 'slug', 'tags', 'template',
        'is_draft', 'is_email_to_subscriber',

        'seo_description', 'seo_keywords', 'seo_robots', 'og_url', 'og_type', 'og_title', 'og_description',

        'post_at', 'expired_at',
    ];

    protected $columnHasRelations = ['user_id'];

    protected $inputDates = ['post_at', 'expired_at'];
    protected $inputBooleans = ['is_draft', 'is_email_to_subscriber'];
    protected $findInSetList = ['tags'];

    protected $files = ['cover_photo', 'og_image'];
    protected $fileOptions = [
        'cover_photo' => ['tag' => 'cover_photo', 'width' => 640, 'height' => 360, 'remove_previous' => true],
        'og_image' => ['tag' => 'og_image', 'width' => 1200, 'height' => 627, 'remove_previous' => true]
    ];

    protected $unClean = ['content'];

    public function __construct(array $attributes = [])
    {
        $this->fillable($this->writableColumns);
        parent::__construct($attributes);
    }

    public function actionRemoveBefore($results)
    {
        foreach ($results as $row) {
            PageView::remove(['page_id' => $row->id]);
            PageClassification::remove(['page_id' => $row->id]);
        }

        return true;
    }

    public function pageCategory()
    {
        return $this->belongsTo('App\Models\PageCategory');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    protected function customQueries($query): void
    {
        $query->join('users', 'pages.user_id', '=', 'users.id');
    }

    protected function customQuerySelectList(): array
    {
        $_param_page_category = $this->_lookForCategory();
        $_param_related_category = $this->_lookForRelatedCategory();
        $category = 'SELECT COUNT(*) FROM page_classifications JOIN page_categories ON page_classifications.page_category_id = page_categories.id WHERE page_classifications.page_id = pages.id';

        return [
            'author' => 'CONCAT(users.first_name, " ", users.last_name)',
            'author_username' => 'users.username',
            'count_category' => '(' . $category . ')',
            'has_category' => $_param_page_category ? 'IF((' . $category . ' AND page_categories.' . $_param_page_category['column'] . ' = "' . $_param_page_category['data'] . '") > 0, 1, 0)' : '0',
            'is_category_enabled' => $_param_page_category ? 'IF((' . $category . ' AND page_categories.' . $_param_page_category['column'] . ' = "' . $_param_page_category['data'] . '" AND page_categories.is_enabled = 1) > 0, 1, 0)' : '0',
            'is_category_related' => $_param_related_category ? 'IF((' . $category . ' AND page_categories.id IN (' . $_param_related_category . ')) > 0, 1, 0)' : '0',
            'is_expired' => 'IF(pages.expired_at IS NOT NULL, IF(pages.expired_at < DATE(NOW()), 1, 0), 0)',
            'is_posted' => 'IF(pages.post_at IS NOT NULL, IF(DATE(NOW()) >= pages.post_at, 1, 0), 0)',
        ];
    }

    private function _lookForCategory()
    {
        if ($this->hasParams('page_category_slug')) {
            $this->params['has_category'] = 1;
            return ['data' => $this->hasParams('page_category_slug'), 'column' => 'slug'];
        }

        if ($this->hasParams('page_category_id')) {
            $this->params['has_category'] = 1;
            return ['data' => $this->hasParams('page_category_id'), 'column' => 'id'];
        }

        return NULL;
    }

    private function _lookForRelatedCategory()
    {
        if ($this->hasParams('related_category')) {
            $this->params['is_category_related'] = 1;
            return implode(",", dbCleanInput($this->hasParams('related_category')));
        }

        return NULL;
    }

    protected function dataFormatting($row)
    {
        $this->addDateFormatting($row);

        $row->url = url('p/' . $row->slug);

        $row->cover_photo = File::lookForFile($row->id, 'pages', 'cover_photo');
        $row->og_image = File::lookForFile($row->id, 'pages', 'og_image');

        $row->categories = PageCategory::fetchAll([
            'include' => ['id' => dbArrayColumns(PageClassification::fetchAll(['page_id' => $row->id]), 'page_category_id')]
        ]);

        return $row;
    }
}
