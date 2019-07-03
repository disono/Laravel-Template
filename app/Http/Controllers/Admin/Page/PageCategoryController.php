<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Admin\Page;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Page\PageCategoryStore;
use App\Http\Requests\Admin\Page\PageCategoryUpdate;
use App\Models\Vendor\Facades\PageCategory;

class PageCategoryController extends Controller
{
    protected $viewType = 'admin';
    private $_pageCategory;

    public function __construct()
    {
        parent::__construct();
        $this->theme = 'page.category';
        $this->_pageCategory = PageCategory::self();
    }

    public function indexAction()
    {
        $this->setHeader('title', 'Page Categories');

        $defaults = [];
        if (!$this->request->get('parent_id')) {
            $defaults['parent_id'] = 0;
        }

        $this->_pageCategory->enableSearch = true;
        return $this->view('index', [
            'parents' => $this->_pageCategory->parents($this->request->get('parent_id')),
            'page_categories' => $this->_pageCategory->fetch(requestValues('search|name|pagination_show|parent_id|is_enabled', $defaults))
        ]);
    }

    public function createAction()
    {
        $this->setHeader('title', 'Create a New Page Category');
        return $this->view('create', [
            'categories' => $this->_pageCategory->formattedCategories(['tab_in_name' => true, 'tab' => ' - - - ']),
        ]);
    }

    public function storeAction(PageCategoryStore $request)
    {
        $inputs = $request->all();
        $this->_pageCategory->setFilesFromRequest($request, $inputs);
        $c = $this->_pageCategory->store($inputs);
        if (!$c) {
            return $this->json(['name' => 'Failed to crate a new category.'], 422, false);
        }

        return $this->json(['redirect' => '/admin/page-categories']);
    }

    public function editAction($id)
    {
        $this->setHeader('title', 'Editing Page Category');
        $this->content['category'] = $this->_pageCategory->single($id);
        if (!$this->content['category']) {
            abort(404);
        }

        $this->content['categories'] = $this->_pageCategory->formattedCategories(['tab_in_name' => true, 'tab' => ' - - - ']);
        return $this->view('edit');
    }

    public function updateAction(PageCategoryUpdate $request)
    {
        $inputs = $request->all();
        $this->_pageCategory->setFilesFromRequest($request, $inputs);

        if ($request->get('id') === $request->get('parent_id')) {
            return $this->json(['parent_id' => 'Invalid parent category.'], 422, false);
        }

        $this->_pageCategory->edit($request->get('id'), $inputs);
        return $this->json('Category is successfully updated.');
    }

    public function updateStatusAction($column, $status, $id)
    {
        if (!in_array($column, ['is_enabled'])) {
            return $this->error(499, 'Status not found.');
        }

        $this->_pageCategory->clearBoolean();
        $this->_pageCategory->clearInteger();
        $this->_pageCategory->edit($id, [$column => $status]);
        return $this->json('Page Category is successfully updated.');
    }

    public function destroyAction($id)
    {
        $this->_pageCategory->remove($id);
        return $this->json('Category is successfully deleted.');
    }
}
