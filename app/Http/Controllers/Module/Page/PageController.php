<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Module\Page;

use App\Http\Controllers\Controller;
use App\Models\Vendor\Facades\Page;
use App\Models\Vendor\Facades\PageView;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PageController extends Controller
{
    private $_page;

    public function __construct()
    {
        $this->theme = 'page';
        parent::__construct();

        $this->_page = Page::self();
    }

    /**
     * Home page
     *
     * @return JsonResponse|Response
     */
    public function homeAction()
    {
        $this->setHeader('title', 'Home');
        return $this->view('home');
    }

    /**
     * List by category page
     *
     * @param $slug
     * @return JsonResponse|Response
     */
    public function categoryAction($slug)
    {
        $this->setHeader('title', ucfirst($slug) . ' Categories');
        return $this->view('list', [
            'pages' => $this->_page->fetch(['page_category_slug' => $slug, 'is_category_enabled' => 1])
        ]);
    }

    /**
     * List old pages
     *
     * @param $year
     * @param $month
     * @return JsonResponse|Response
     */
    public function archiveAction($year, $month)
    {
        return $this->view('list', [
            'pages' => $this->_page->fetch(['raw_year' => $year, 'raw_month' => $month])
        ]);
    }

    /**
     * Show page details
     *
     * @param $slug
     * @return JsonResponse|Response|void
     */
    public function showAction($slug)
    {
        $view = 'show';
        $page = $this->_page->single($slug, 'slug');
        if (!$page) {
            return $this->error(404, exceptionMessages('PAGE_NOT_FOUND'));
        }

        // custom views
        if ($page->template) {
            $view = 'templates.' . $page->template;
        }

        // page title
        $this->setHeader('title', ucfirst($page->name));

        // SEO and og meta
        $this->setAppView('page_description', $page->seo_description);
        $this->setAppView('page_keywords', $page->seo_keywords);
        $this->setAppView('seo_robots', $page->seo_robots);
        $this->setAppView('og_url', $page->og_url);
        $this->setAppView('og_type', $page->og_type);
        $this->setAppView('og_title', $page->og_title);
        $this->setAppView('og_description', $page->seo_description);

        // og image
        if ($page->og_image->exists) {
            $this->setAppView('og_image', $page->og_image->primary);
            $this->setAppView('og_image_width', $page->og_image->meta->img_width);
            $this->setAppView('og_image_height', $page->og_image->meta->img_height);
        }

        // save page view per device or user
        $this->_savePageView($page);

        return $this->view($view, [
            'page' => $page,
            'related_articles' => $this->_page->fetchAll([
                'related_category' => dbArrayColumns($page->categories), 'take_query' => 3, 'exclude' => ['id' => [$page->id]]
            ]),
        ]);
    }

    private function _savePageView($page)
    {
        PageView::self()->log($page);
    }
}
