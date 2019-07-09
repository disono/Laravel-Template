<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\API\V1\Page;

use App\Http\Controllers\API\APIController;
use App\Models\Vendor\Facades\Page;
use App\Models\Vendor\Facades\PageView;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PageController extends APIController
{
    private $_page;

    public function __construct()
    {
        parent::__construct();
        $this->_page = Page::self();
    }

    /**
     * List by category page
     *
     * @param $category
     * @return JsonResponse|Response
     */
    public function categoryAction($category)
    {
        return $this->json($this->_page->fetch(['page_category_slug' => $category, 'is_category_enabled' => 1]));
    }

    /**
     * List old pages
     *
     * @param $year
     * @param $month
     * @return JsonResponse
     */
    public function archiveAction($year, $month)
    {
        return $this->json($this->_page->fetch(['raw_year' => $year, 'raw_month' => $month]));
    }

    /**
     * Show page details
     *
     * @param $slug
     * @return JsonResponse
     */
    public function showAction($slug)
    {
        $page = $this->_page->single($slug, 'slug');
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
