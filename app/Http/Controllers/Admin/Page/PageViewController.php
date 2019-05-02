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
        return $this->view('index', [
            'page_views' => PageView::fetch(requestValues('search|page_id'))
        ]);
    }
}
