<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Application\Role\RoleStore;
use App\Http\Requests\Admin\Application\Role\RoleUpdate;
use App\Models\Role;

class RoleController extends Controller
{
    protected $viewType = 'admin';

    public function __construct()
    {
        parent::__construct();
        $this->theme = 'settings.role';
    }

    public function indexAction()
    {
        $this->setHeader('title', 'Roles');
        $roles = new Role();
        $roles->enableSearch = true;
        return $this->view('index', [
            'roles' => $roles->fetch(requestValues('search|pagination_show|name'))
        ]);
    }

    public function createAction()
    {
        $this->setHeader('title', 'Add New User Role');
        return $this->view('create');
    }

    public function storeAction(RoleStore $request)
    {
        $role = (new Role())->store($request->all());
        if (!$role) {
            return $this->json(['name' => 'Failed to crate a new role.'], 422, false);
        }

        return $this->json(['redirect' => '/admin/role/edit/' . $role->id]);
    }

    public function editAction($id)
    {
        $role = (new Role())->single($id);
        if (!$role) {
            abort(404);
        }

        $this->setHeader('title', 'Editing ' . $role->name);
        return $this->view('edit', ['role' => $role]);
    }

    public function updateAction(RoleUpdate $request)
    {
        (new Role())->edit($request->get('id'), $request->all());
        return $this->json('Role is successfully updated.');
    }

    public function destroyAction($id)
    {
        (new Role())->remove($id);
        return $this->json('Role is successfully deleted.');
    }
}
