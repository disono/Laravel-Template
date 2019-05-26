<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Admin\Page;

use App\Http\Controllers\Controller;
use App\Models\PageView;

class PageViewController extends Controller
{
    protected $viewType = 'admin';

    public function __construct()
    {
        parent::__construct();
        $this->theme = 'page.page_views';
    }

    public function indexAction()
    {
        $this->setHeader('title', 'Page Views');
        $pageView = new PageView();
        $pageView->enableSearch = true;
        $pageView->setNewWritableColumn('created_at');
        return $this->view('index', [
            'page_views' => $pageView->fetch(requestValues('search|pagination_show|page_id|page_name|
                http_referrer|current_url|ip_address|platform|browser|created_at'))
        ]);
    }
}
