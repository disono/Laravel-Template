<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
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
    private $_user;

    public function __construct()
    {
        parent::__construct();
        $this->theme = 'user';
        $this->_user = new User();
    }

    public function indexAction()
    {
        $this->setHeader('title', 'Users');
        $this->_user->enableSearch = true;
        return $this->view('index', [
            'users' => $this->_user->fetch(requestValues('search|pagination_show|full_name|email|username|role_id|
                is_email_verified|is_account_activated|is_account_enabled')),
            'roles' => (new Role())->fetchAll()
        ]);
    }

    public function createAction()
    {
        $this->setHeader('title', 'Register New User');
        return $this->view('create', [
            'roles' => (new Role())->fetchAll(),
            'countries' => (new Country())->fetchAll(),
            'cities' => (new City())->fetchAll()
        ]);
    }

    public function storeAction(UserStore $request)
    {
        $user = $this->_user->store($this->_formInputs($request));
        if (!$user) {
            return $this->json(['first_name' => 'Failed to crate a new user.'], 422, false);
        }

        return $this->json(['redirect' => '/admin/user/edit/' . $user->id]);
    }

    private function _formInputs($request)
    {
        $inputs = $request->all();
        $inputs['profile_picture'] = $request->file('profile_picture');

        return $inputs;
    }

    public function editAction($id)
    {
        $user = $this->_user->single($id);
        if (!$user) {
            abort(404);
        }

        $this->setHeader('title', 'Editing ' . $user->full_name);
        return $this->view('edit', [
            'user' => $user,
            'roles' => (new Role())->fetchAll(),
            'countries' => (new Country())->fetchAll(),
            'cities' => (new City())->fetchAll()
        ]);
    }

    public function updateAction(UserUpdate $request)
    {
        $this->_user->edit($request->get('id'), $this->_formInputs($request));
        return $this->json('User is successfully updated.');
    }

    public function updateColumnAction($id, $column, $value)
    {
        if (!is_numeric($value)) {
            return $this->redirect();
        }

        if (!in_array($column, ['is_email_verified', 'is_account_activated', 'is_account_enabled'])) {
            return $this->redirect();
        }

        $this->_user->edit($id, [$column => (int)$value], false);
        return $this->redirect();
    }

    public function destroyAction($id)
    {
        $this->_user->remove($id);
        return $this->json('User is successfully deleted.');
    }
}
