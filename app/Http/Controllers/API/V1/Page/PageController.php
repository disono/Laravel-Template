<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\API\V1\Page;

use App\Http\Controllers\API\APIController;
use App\Models\Page;
use App\Models\PageView;

class PageController extends APIController
{
    /**
     * List by category page
     *
     * @param $category
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function categoryAction($category)
    {
        return $this->json(Page::fetch(['page_category_slug' => $category]));
    }

    /**
     * List old pages
     *
     * @param $year
     * @param $month
     * @return \Illuminate\Http\JsonResponse
     */
    public function archiveAction($year, $month)
    {
        return $this->json(Page::fetch(['raw_year' => $year, 'raw_month' => $month]));
    }

    /**
     * Show page details
     *
     * @param $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function showAction($slug)
    {
        $page = Page::single($slug, 'slug');
        if (!$page) {
            return $this->json(exceptionMessages('PAGE_NOT_FOUND'), 404);
        }

        // save page view per device or user
        $this->_savePageView($page);

        return $this->json($page);
    }

    private function _savePageView($page)
    {
        PageView::log($page);
    }
}
