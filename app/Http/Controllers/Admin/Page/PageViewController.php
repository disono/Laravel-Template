<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Admin\Page;

use App\Http\Controllers\Controller;
use App\Models\Vendor\Facades\PageView;

class PageViewController extends Controller
{
    protected $viewType = 'admin';
    private $pageView;

    public function __construct()
    {
        parent::__construct();
        $this->theme = 'page.views';

        $this->pageView = PageView::self();
    }

    public function indexAction()
    {
        $this->setHeader('title', 'Page Views');
        $this->pageView->enableSearch = true;
        $this->pageView->setWritableColumn('created_at');
        return $this->view('index', [
            'page_views' => $this->pageView->fetch(requestValues('search|pagination_show|page_id|page_name|
                http_referrer|current_url|ip_address|platform|browser|created_at'))
        ]);
    }

    public function destroyAction($id)
    {
        $this->pageView->remove($id);
        return $this->json('Page view is successfully deleted.');
    }
}
