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
        $pageCategories = new PageCategory();
        $pageCategories->enableSearch = true;
        return $this->view('index', [
            'page_categories' => $pageCategories->nestedToTabs(requestValues('search|name|pagination_show',
                ['include_tab' => false, 'strong' => true]
            ))
        ]);
    }

    public function createAction()
    {
        $this->setHeader('title', 'Create a New Page Category');
        return $this->view('create', [
            'categories' => (new PageCategory())->fetchAll(),
        ]);
    }

    public function storeAction(PageCategoryStore $request)
    {
        $c = (new PageCategory())->store($request->all());
        if (!$c) {
            return $this->json(['name' => 'Failed to crate a new category.'], 422, false);
        }

        return $this->json(['redirect' => '/admin/page-category/edit/' . $c->id]);
    }

    public function editAction($id)
    {
        $this->setHeader('title', 'Editing Page Category');
        $this->content['category'] = (new PageCategory())->single($id);
        if (!$this->content['category']) {
            abort(404);
        }

        $this->content['categories'] = (new PageCategory())->fetchAll();
        return $this->view('edit');
    }

    public function updateAction(PageCategoryUpdate $request)
    {
        (new PageCategory())->edit($request->get('id'), $request->all());
        return $this->json('Category is successfully updated.');
    }

    public function destroyAction($id)
    {
        (new PageCategory())->remove($id);
        return $this->json('Category is successfully deleted.');
    }
}
