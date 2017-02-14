<?php

namespace App\Http\Controllers\Admin\Page;

use App\Models\PageView;
use App\Http\Controllers\Controller;

class PageViewController extends Controller
{
    public function __construct()
    {
        $this->view = 'page.';
        $this->view_type = 'admin';
        parent::__construct();
    }

    /**
     * List of data
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $this->title = 'Page Views';

        $this->content['page_views'] = PageView::get(request_options([
            'search', 'date_range_from', 'date_range_to'
        ]));

        $this->content['page_view_object'] = PageView::getAll(['object' => true]);

        return $this->response('views');
    }
}
