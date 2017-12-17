<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Admin;

use App\Events\EventResetPassword;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Country;
use App\Models\Role;
use App\Models\Slug;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->view = 'user.';
        $this->view_type = 'admin';
        parent::__construct();
    }

    /**
     * List data
     *
     * @return mixed
     */
    public function index()
    {
        $data = User::fetch(request_options('id|search|country_id|enabled|email_confirmed|is_activated|date_from|date_to|role'));

        if ($this->request->ajax()) {
            $this->content = $data;
        } else {
            $this->title = 'Users';
            $this->content['countries'] = Country::all();
            $this->content['role'] = Role::all();
            $this->content['users'] = $data;
        }
        return $this->response('index');
    }

    /**
     * User locator
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function map()
    {
        $this->title = 'Locator';
        $this->content['role'] = Role::all();
        return $this->response('map');
    }

    /**
     * Create new data
     *
     * @return mixed
     */
    public function create()
    {
        $this->title = 'Create User';
        $this->content['roles'] = Role::all();
        return $this->response('create');
    }

    /**
     * Store new data
     *
     * @param Requests\Admin\UserCreate $create
     * @return mixed
     */
    public function store(Requests\Admin\UserCreate $create)
    {
        $data = $create->all();
        $user = User::insertGetId([
            'first_name' => ucfirst($data['first_name']),
            'last_name' => ucfirst($data['last_name']),
            'phone' => $data['phone'],
            'email' => $data['email'],
            'role' => $data['role'],
            'password' => bcrypt($data['password']),
            'birthday' => sql_date($data['birthday'], true),

            'email_confirmed' => (isset($data['email_confirmed'])) ? 1 : 0,
            'address' => $data['address'],

            'enabled' => 1,
        ]);

        if ($user) {
            $user = User::find($user);
        }

        // avatar
        $image = $create->file('image');
        if ($image && $user) {
            // image
            $upload_image = upload_image($image, [
                'user_id' => $user->id,
                'source_id' => $user->id,
                'title' => $user->first_name . ' ' . $user->last_name,
                'type' => 'user',
                'crop_auto' => true
            ], $user->image_id);

            // save new avatar
            if ($upload_image) {
                $user->image_id = $upload_image;
                $user->save();
            }
        }

        // slug or username
        if ($user && isset($data['username'])) {
            Slug::store([
                'source_id' => $user->id,
                'source_type' => 'user',
                'name' => $data['username']
            ]);
        } else {
            return $this->redirectResponse()->withErrors([
                'username' => 'Please select different username.'
            ]);
        }

        return $this->redirectResponse('admin/users');
    }

    /**
     * Edit data
     *
     * @param $id
     * @return mixed
     */
    public function edit($id)
    {
        $data = User::single($id);
        if (!$data) {
            abort(404);
        }

        $this->title = 'Edit User';
        $this->content['user'] = $data;
        $this->content['roles'] = Role::all();
        return $this->response('edit');
    }

    /**
     * Store new data
     *
     * @param Requests\Admin\UserUpdate $request
     * @return mixed
     */
    public function update(Requests\Admin\UserUpdate $request)
    {
        $data = $request->all();

        // inputs to update
        $inputs = [
            'first_name' => ucfirst($data['first_name']),
            'last_name' => ucfirst($data['last_name']),
            'phone' => $data['phone'],
            'email' => $data['email'],
            'role' => $data['role'],
            'birthday' => sql_date($data['birthday'], true),

            'email_confirmed' => (isset($data['email_confirmed'])) ? 1 : 0,
            'address' => $data['address'],
        ];

        // password
        if ($request->get('password')) {
            $inputs['password'] = bcrypt($data['password']);
        }

        // update user
        $user = User::find($request->get('id'));

        // avatar
        $image = $request->file('image');
        if ($image && $user) {
            // image
            $upload_image = upload_image($image, [
                'user_id' => $user->id,
                'source_id' => $user->id,
                'title' => $user->first_name . ' ' . $user->last_name,
                'type' => 'user',
                'crop_auto' => true
            ], $user->image_id);

            // save new avatar
            if ($upload_image) {
                $user->image_id = $upload_image;
                $user->save();
            }
        }

        // slug or username
        if (isset($data['username'])) {
            Slug::edit(null, [
                'name' => $data['username']
            ], [
                'source_id' => $user->id,
                'source_type' => 'user'
            ]);
        } else {
            return redirect()->withErrors([
                'username' => 'Please select different username.'
            ]);
        }

        User::where('id', $request->get('id'))->update($inputs);
        return $this->redirectResponse('admin/users');
    }

    /**
     * Login user
     *
     * @param $id
     * @return bool
     */
    public function login($id)
    {
        auth()->logout();
        auth()->loginUsingId($id);
        return $this->redirectResponse('/');
    }

    /**
     * Edit password
     *
     * @param $id
     * @return mixed
     */
    public function passwordEdit($id)
    {
        $this->content['user'] = User::single($id);
        if (!$this->content['user']) {
            abort(404);
        }

        $this->title = 'Reset Password';
        return $this->response('password_edit');
    }

    /**
     * Update password
     *
     * @param Requests\Web\UserUpdatePassword $request
     * @return mixed
     */
    public function passwordUpdate(Requests\Web\UserUpdatePassword $request)
    {
        $update = User::edit($request->get('id'), $request->only([
            'password'
        ]));

        if ($update) {
            $user = User::single($request->get('id'));
            $this->_addNewAuth($user, $request);

            // send email for password reset
            event(new EventResetPassword([
                'user' => $user
            ]));
        }

        return $this->redirectResponse('admin/users');
    }

    /**
     * Add new auth
     *
     * @param $user
     * @param $request
     * @return mixed
     */
    private function _addNewAuth($user, $request)
    {
        $user->new_password = $request->get('password');
        $user->sent_to = $request->get('email');
        return $user;
    }

    /**
     * Confirm
     *
     * @param Request $request
     * @return bool
     */
    public function confirm(Request $request)
    {
        $user = User::find($request->get('id'));
        if (!$user) {
            return redirect()->back();
        }

        // email
        if ($request->get('type') === 'email') {
            $user->email_confirmed = (($user->email_confirmed) ? 0 : 1);
            $user->save();
        }

        // phone
        if ($request->get('type') === 'phone') {
            $user->enabled = (($user->phone_confirmed) ? 0 : 1);
            $user->save();
        }

        // account enabled
        if ($request->get('type') === 'account') {
            $user->enabled = (($user->enabled) ? 0 : 1);
            $user->save();
        }

        // account is activated
        if ($request->get('type') === 'is_activated') {
            $user->is_activated = (($user->is_activated) ? 0 : 1);
            $user->save();
        }

        return $this->redirectResponse();
    }

    /**
     * Delete data
     *
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function destroy($id)
    {
        User::remove($id);

        if (request()->ajax()) {
            return success_json_response('Successfully deleted user.');
        }

        return redirect()->back();
    }
}
