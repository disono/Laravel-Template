<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Admin\Page;

use App\Http\Controllers\Controller;
use App\Models\PageView;
use App\Models\Subscriber;
use App\Models\User;

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
        $this->content['page_views'] = PageView::fetch(request_options([
            'search', 'date_range_from', 'date_range_to'
        ]));
        $this->content['page_view_object'] = PageView::getAll(['object' => true]);
        return $this->response('views');
    }

    /**
     * Chart data
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function chart()
    {
        $this->response_type = 'json';

        $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        $cleanSales = [
            'label' => 'Page Views - ' . date('Y'),
            'total_active_user' => User::fetch(['enabled' => 1, 'email_confirmed' => 1, 'object' => true])->count(),
            'total_inactive_user' => User::fetch(['enabled' => 0, 'object' => true])->count(),
            'total_subscriber' => Subscriber::fetch(['object' => true])->count(),
            'labels' => [],
            'data' => []
        ];
        foreach ($months as $m) {
            $cleanSales['labels'][] = $m;
            $cleanSales['data'][] = PageView::fetch(request_options([
                'date_range_from', 'date_range_to', 'search'
            ], ['object' => true, 'search_month' => $m, 'search_year' => date('Y')]))->count();
        }

        $this->content = $cleanSales;
        return $this->response();
    }
}
