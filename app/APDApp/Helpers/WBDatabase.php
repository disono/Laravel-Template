<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\APDApp\Helpers;

use App\Models\SlugExclude;
use Illuminate\Pagination\LengthAwarePaginator;

class WBDatabase
{
    /**
     * Filter all ids
     *
     * @param array $data
     * @param null $column_name
     * @return array
     */
    public static function filterID($data = [], $column_name = null)
    {
        $ids = [];
        foreach ($data as $row) {
            $ids[] = $row->$column_name;
        }

        return $ids;
    }

    /**
     * Custom pagination
     *
     * @param $collections
     * @param int $pagination_num
     * @return LengthAwarePaginator
     */
    public static function paginate($collections, $pagination_num = 0)
    {
        if (!$pagination_num) {
            $pagination_num = config_per_page();
        }

        return $collections->paginate($pagination_num);
    }

    /**
     * Exclude slug
     *
     * @return string
     */
    public static function excludeSlug()
    {
        $slugs = SlugExclude::all();
        $excludes = '';

        foreach ($slugs as $row) {
            $excludes .= $row->name . ',';
        }

        $excludes = rtrim($excludes, ',');
        $excludes = ltrim($excludes, ',');

        return $excludes;
    }
}