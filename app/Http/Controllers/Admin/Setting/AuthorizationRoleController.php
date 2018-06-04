<?php
/**
 * @author          Archie, Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (webmons.com), 2016-2018
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Models\AuthorizationRole;
use App\Models\Role;
use Illuminate\Support\Facades\Route;

class AuthorizationRoleController extends Controller
{
    protected $viewType = 'admin';

    public function __construct()
    {
        parent::__construct();
        $this->theme = 'settings.authorization_role';
    }

    public function editAction($role_id)
    {
        $role = Role::single($role_id);
        if (!$role) {
            abort(404);
        }

        $authorizations = $this->_routeNames(AuthorizationRole::fetchAll(['role_id' => $role->id]));

        $this->setHeader('title', 'Updating Authorization for ' . $role->name);
        return $this->view('edit', ['role' => $role, 'authorizations' => $authorizations, 'routes' => $this->_routesName()]);
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
        if (!AuthorizationRole::remove($role->id, 'role_id')) {
            return $this->json('Failed to reset the authentication roles.', 422);
        }

        // save new auth access
        $_insertNames = [];
        $_names = $this->_routesName(true);
        foreach ($route_names as $name) {
            if (in_array($name, $_names)) {
                $_insertNames[] = ['route' => $name, 'role_id' => $role->id];
            }
        }
        AuthorizationRole::insert($_insertNames);

        return $this->json('Save successfully.');
    }

    private function _routesName($nameOnly = false)
    {
        $names = [];

        foreach (Route::getRoutes()->getRoutes() as $route) {
            $action = $route->getAction();

            if (array_key_exists('as', $action)) {
                if (strpos($action['as'], 'admin.') !== false) {
                    $data = new \stdClass();
                    $data->id = str_random(32);
                    $data->name = ucwords(str_replace('.', ' ', str_replace('admin.', '', $action['as'])));
                    $data->value = $action['as'];

                    $names[] = ($nameOnly) ? $action['as'] : $data;
                }
            }
        }

        return $names;
    }

    private function _routeNames($auth)
    {
        $names = [];

        foreach ($auth as $a) {
            $names[] = $a->route;
        }

        return $names;
    }
}
