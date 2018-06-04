<?php
/**
 * @author          Archie, Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (webmons.com), 2016-2018
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Admin\Page;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Page\PageCategoryStore;
use App\Http\Requests\Admin\Page\PageCategoryUpdate;
use App\Models\PageCategory;

class PageCategoryController extends Controller
{
    protected $viewType = 'admin';

    public function __construct()
    {
        parent::__construct();
        $this->theme = 'page_category';
    }

    public function indexAction()
    {
        $this->setHeader('title', 'Page Categories');
        return $this->view('index', [
            'page_categories' => PageCategory::nestedToTabs([
                'include_tab' => false, 'strong' => true, 'search' => $this->request->get('search')
            ])
        ]);
    }

    public function createAction()
    {
        $this->setHeader('title', 'Create a New Page Category');
        return $this->view('create', [
            'categories' => PageCategory::fetchAll(),
        ]);
    }

    public function storeAction(PageCategoryStore $request)
    {
        $c = PageCategory::store($request->all());
        if (!$c) {
            return $this->json(['name' => 'Failed to crate a new category.'], 422, false);
        }

        return $this->json(['redirect' => '/admin/page-category/edit/' . $c->id]);
    }

    public function editAction($id)
    {
        $this->setHeader('title', 'Editing Page Category');
        $this->content['category'] = PageCategory::single($id);
        if (!$this->content['category']) {
            abort(404);
        }

        $this->content['categories'] = PageCategory::fetchAll();
        return $this->view('edit');
    }

    public function updateAction(PageCategoryUpdate $request)
    {
        PageCategory::edit($request->get('id'), $request->all());
        return $this->json('Category is successfully updated.');
    }

    public function destroyAction($id)
    {
        PageCategory::remove($id);
        return $this->json('Category is successfully deleted.');
    }
}
