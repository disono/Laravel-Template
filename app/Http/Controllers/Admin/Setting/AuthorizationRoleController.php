<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Models\Vendor\Facades\AuthorizationRole;
use App\Models\Vendor\Facades\Role;
use Illuminate\Support\Facades\Route;

class AuthorizationRoleController extends Controller
{
    protected $viewType = 'admin';
    protected $authorizationRoles;

    public function __construct()
    {
        parent::__construct();
        $this->theme = 'settings.authorization_role';

        $this->authorizationRoles = AuthorizationRole::self();
    }

    public function editAction($role_id)
    {
        $role = Role::single($role_id);
        if (!$role) {
            abort(404);
        }

        $authorizations = $this->_routeNames($this->authorizationRoles->fetchAll(['role_id' => $role->id]));

        $this->setHeader('title', 'Updating Authorization for ' . $role->name);
        return $this->view('edit', ['role' => $role, 'authorizations' => $authorizations, 'routes' => collect($this->_routesInCategory())]);
    }

    private function _routeNames($auth)
    {
        $names = [];

        foreach ($auth as $a) {
            $names[] = $a->route;
        }

        return $names;
    }

    private function _routesInCategory()
    {
        $categories = [];

        foreach ($this->_routesName() as $route) {
            $categories[$route->category_slug]['category_name'] = strtoupper($route->category_name);
            $categories[$route->category_slug]['data'][] = $route;
        }

        return $categories;
    }

    private function _routesName($nameOnly = false)
    {
        $names = [];

        foreach (Route::getRoutes()->getRoutes() as $route) {
            $action = $route->getAction();

            if (array_key_exists('as', $action)) {
                $_name = explode('.', $action['as']);

                if ($_name[0] === 'admin') {
                    $data = new \stdClass();
                    $data->id = str_random(32);
                    $data->name = strtoupper(str_replace('.', ' ', $this->_fromUcToSpace(str_replace('admin.', '', $action['as']))));
                    $data->value = $action['as'];
                    $data->category_slug = $_name[1];
                    $data->category_name = $this->_fromUcToSpace($_name[1]);

                    $names[] = ($nameOnly) ? $action['as'] : $data;
                }
            }
        }

        return $names;
    }

    private function _fromUcToSpace($s)
    {
        return preg_replace('/(?<!\ )[A-Z]/', ' $0', $s);
    }

    public function updateAction()
    {
        $route_names = $this->request->get('route_name');
        if (!is_array($route_names)) {
            return $this->json('Invalid authentication names.', 422);
        }

        // is role exists
        $role = Role::single($this->request->get('role_id'));
        if (!$role) {
            return $this->json('Invalid role.', 422);
        }

        // clear old auth access
        if ($this->authorizationRoles->fetch(['object' => true, 'role_id' => $role->id])->count()) {
            if (!$this->authorizationRoles->remove($role->id, 'role_id')) {
                return $this->json('Failed to reset the authentication roles.', 422);
            }
        }

        // save new auth access
        $_insertNames = [];
        $_names = $this->_routesName(true);
        foreach ($route_names as $name) {
            if (in_array($name, $_names)) {
                $_insertNames[] = ['route' => $name, 'role_id' => $role->id];
            }
        }

        $this->authorizationRoles->insert($_insertNames);
        return $this->json('Save successfully.');
    }
}
