<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Admin\Page;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Page\PageStore;
use App\Http\Requests\Admin\Page\PageUpdate;
use App\Models\Vendor\Facades\Page;
use App\Models\Vendor\Facades\PageCategory;
use App\Models\Vendor\Facades\PageClassification;

class PageController extends Controller
{
    protected $viewType = 'admin';
    private $_page;

    public function __construct()
    {
        parent::__construct();
        $this->theme = 'page';
        $this->_page = Page::self();

        $this->middleware(function ($request, $next) {
            if (in_array(request()->route()->getName(), ['admin.page.create', 'admin.page.edit'])) {
                $this->setAppView('app_scripts', [
                    'assets/js/lib/tinymce/tinymce.min.js',
                    'assets/js/app/tinymce.js',
                    'assets/js/vue/page.admin.js'
                ]);
            }

            return $next($request);
        });
    }

    public function indexAction()
    {
        $this->setHeader('title', 'Pages');

        $defaults = [
            'find_in_set' => true
        ];

        $this->_page->enableSearch = true;
        return $this->view('index', [
            'pages' => $this->_page->fetch(requestValues('name|slug|search|page_category_id|is_draft|is_email_to_subscriber|tags', $defaults)),
            'categories' => PageCategory::formattedCategories(['tab_in_name' => true, 'tab' => ' - - - '])
        ]);
    }

    public function createAction()
    {
        $this->setHeader('title', 'Create a New Page');
        return $this->view('create', [
            'categories' => PageCategory::formattedCategories(['tab_numeric' => 16])
        ]);
    }

    public function storeAction(PageStore $request)
    {
        $page = $this->_page->store($this->_formInputs($request));
        if (!$page) {
            return $this->json(['name' => 'Failed to crate a new page.'], 422, false);
        }

        // add new classification
        $this->_pageClassification($page->id, $request->get('page_category_id'));

        return $this->json(['redirect' => '/admin/page/edit/' . $page->id]);
    }

    public function editAction($id)
    {
        $this->setHeader('title', 'Editing Page');
        $this->content['page'] = $this->_page->single($id);
        if (!$this->content['page']) {
            abort(404);
        }

        $this->content['categories'] = PageCategory::formattedCategories(['tab_numeric' => 16]);
        $this->content['classifications'] = dbArrayColumns($this->content['page']->categories, 'id');
        return $this->view('edit');
    }

    public function updateAction(PageUpdate $request)
    {
        $this->_page->edit($request->get('id'), $this->_formInputs($request));

        // add new classification
        $this->_pageClassification($request->get('id'), $request->get('page_category_id'));

        return $this->json('Page is successfully updated.');
    }

    public function updateStatusAction($column, $status, $id)
    {
        if (!in_array($column, ['is_draft', 'is_email_to_subscriber'])) {
            return $this->error(499, 'Status not found.');
        }

        $this->_page->clearBoolean();
        $this->_page->edit($id, [$column => $status]);
        return $this->json('Page is successfully updated.');
    }

    public function destroyAction($id)
    {
        $this->_page->remove($id);
        return $this->json('Page is successfully deleted.');
    }

    private function _formInputs($request)
    {
        $inputs = $request->all();
        $inputs['user_id'] = __me()->id;

        return $inputs;
    }

    private function _pageClassification($page_id, $categories = [])
    {
        PageClassification::where('page_id', $page_id)->delete();

        foreach ($categories as $id) {
            PageClassification::insert([
                'page_id' => $page_id,
                'page_category_id' => $id
            ]);
        }
    }
}
