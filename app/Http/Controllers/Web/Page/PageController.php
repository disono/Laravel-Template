<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */

namespace App\Http\Controllers\Web\Page;

use App\Http\Controllers\Controller;
use App\Models\Page;
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
    public function getHome()
    {
        PageView::store();
        return $this->response('home');
    }

    /**
     * Show page
     *
     * @param $function
     * @param null $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getShow($function, $slug = null)
    {
        $template = 'show';
        $page = null;

        if (!$slug) {
            $page = Page::single($function, 'slug');
        } else {
            $page = Page::get([
                'category_slug' => $function,
                'slug' => $slug,
                'single' => true
            ]);
        }

        if (!$page) {
            abort(404);
        }

        // custom template
        if ($page->template) {
            $template = 'templates.' . $page->template;
        }

        $this->title = $page->name;
        $this->content['page'] = $page;

        // SEO
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
    public function streamVideo($file)
    {
        return video_stream('assets/video/' . $file);
    }
}
