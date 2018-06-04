<?php
/**
 * @author          Archie, Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (webmons.com), 2016-2018
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UserStore;
use App\Http\Requests\Admin\User\UserUpdate;
use App\Models\City;
use App\Models\Country;
use App\Models\Role;
use App\Models\User;

class UserController extends Controller
{
    protected $viewType = 'admin';

    public function __construct()
    {
        parent::__construct();
        $this->theme = 'user';
    }

    public function indexAction()
    {
        $this->setHeader('title', 'Users');
        return $this->view('index', [
            'users' => User::fetch(requestValues('search|role_id')),
            'roles' => Role::fetchAll()
        ]);
    }

    public function createAction()
    {
        $this->setHeader('title', 'Register New User');
        return $this->view('create', [
            'roles' => Role::fetchAll(),
            'countries' => Country::fetchAll(),
            'cities' => City::fetchAll()
        ]);
    }

    public function storeAction(UserStore $request)
    {
        $user = User::store($this->_formInputs($request));
        if (!$user) {
            return $this->json(['first_name' => 'Failed to crate a new user.'], 422, false);
        }

        return $this->json(['redirect' => '/admin/user/edit/' . $user->id]);
    }

    public function editAction($id)
    {
        $user = User::single($id);
        if (!$user) {
            abort(404);
        }

        $this->setHeader('title', 'Editing ' . $user->full_name);
        return $this->view('edit', [
            'user' => $user,
            'roles' => Role::fetchAll(),
            'countries' => Country::fetchAll(),
            'cities' => City::fetchAll()
        ]);
    }

    public function updateAction(UserUpdate $request)
    {
        User::edit($request->get('id'), $this->_formInputs($request));
        return $this->json('User is successfully updated.');
    }

    public function updateColumnAction($id, $column, $value)
    {
        if (!is_numeric($value)) {
            return $this->redirect();
        }

        User::edit($id, [$column => (int)$value], null, false);
        return $this->redirect();
    }

    public function destroyAction($id)
    {
        User::remove($id);
        return $this->json('User is successfully deleted.');
    }

    private function _formInputs($request)
    {
        $inputs = $request->all();
        $inputs['profile_picture'] = $request->file('profile_picture');

        return $inputs;
    }
}
