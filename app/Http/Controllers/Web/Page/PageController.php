<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Web\Page;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageCategory;
use App\Models\PageView;

class PageController extends Controller
{
    public function __construct()
    {
        $this->view = 'page.';
        parent::__construct();
    }

    /**
     * Home page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function homeAction()
    {
        PageView::store();
        return $this->response('home');
    }

    /**
     * List of pages
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexAction()
    {
        $options = [];

        // page category
        $this->content['category'] = null;
        if ($this->get('category_slug')) {
            $this->content['category'] = PageCategory::single($this->get('category_slug'), 'slug');

            if (!$this->content['category']) {
                abort(404);
            }
        }

        // list of pages
        $this->content['pages'] = Page::fetch(request_options([
            'search', 'category_slug', 'search_month', 'search_year'
        ], $options));
        return $this->response('index');
    }

    /**
     * Show page
     *
     * @param $function
     * @param null $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAction($function, $slug = null)
    {
        $template = 'show';
        $page = null;

        if (!$slug) {
            $page = Page::single($function, 'slug');
        } else {
            $page = Page::fetch([
                'category_slug' => $function,
                'slug' => $slug,
                'single' => true
            ]);
        }

        // page not found
        if (!$page) {
            if (request()->ajax()) {
                return failed_json_response('Page not found.');
            } else {
                abort(404);
            }
        }

        // custom template
        if ($page->template) {
            $template = 'templates.' . $page->template;
        }

        // page details
        $this->title = $page->name;
        $this->content['page'] = $page;
        $this->content['page_description'] = str_limit(strip_tags($page->description), 155);

        PageView::store();
        return $this->response($template);
    }

    /**
     * Stream video
     *
     * @param $file
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function streamVideoAction($file)
    {
        return video_stream('private/' . $file);
    }
}
