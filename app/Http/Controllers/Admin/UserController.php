<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
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
    /**
     * List data
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $content['title'] = app_title('Users');
        $content['request'] = $request;
        $content['countries'] = Country::all();

        $options = [];
        if ($request->get('search')) {
            $options['search'] = $request->get('search');
        }
        if (is_numeric($request->get('country_id'))) {
            $options['country_id'] = $request->get('country_id');
        }
        if (is_numeric($request->get('enabled'))) {
            $options['enabled'] = $request->get('enabled');
        }
        if (is_numeric($request->get('email_confirmed'))) {
            $options['email_confirmed'] = $request->get('email_confirmed');
        }
        if ($request->get('branch_id')) {
            $options['branch_id'] = $request->get('branch_id');
        }
        if ($request->get('date_from') && $request->get('date_to')) {
            $options['date_from'] = $request->get('date_from');
            $options['date_to'] = $request->get('date_to');
        }

        $content['users'] = User::get($options);

        return admin_view('user.index', $content);
    }

    /**
     * Create new data
     *
     * @param Request $request
     * @return mixed
     */
    public function create(Request $request)
    {
        $content['title'] = app_title('Create User');
        $content['request'] = $request;
        $content['roles'] = Role::all();

        return admin_view('user.create', $content);
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
        if ($create->file('image') && $user) {
            // image
            $upload_image = upload_image($create->file('image'), [
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
            return redirect()->withErrors([
                'username' => 'Please select different username.'
            ]);
        }

        return redirect('admin/users');
    }

    /**
     * Edit data
     *
     * @param $id
     * @return mixed
     */
    public function edit($id)
    {
        $content['title'] = app_title('Edit User');
        $content['user'] = User::single($id);

        if (!$content['user']) {
            abort(404);
        }

        $content['roles'] = Role::all();

        return admin_view('user.edit', $content);
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
        if ($request->file('image') && $user) {
            // image
            $upload_image = upload_image($request->file('image'), [
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
        return redirect('admin/users');
    }

    /**
     * Login user
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function login($id)
    {
        auth()->logout();
        auth()->loginUsingId($id);

        return redirect('/');
    }

    /**
     * Edit password
     *
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function passwordEdit(Request $request, $id)
    {
        $content['title'] = app_title('Reset Password');
        $content['request'] = $request;
        $content['user'] = User::single($id);

        if (!$content['user']) {
            abort(404);
        }

        return admin_view('user.password_edit', $content);
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
            $user->new_password = $request->get('password');
            $user->sent_to = $request->get('email');

            // send email for password reset
            event(new EventResetPassword([
                'user' => $user
            ]));
        }

        return redirect('admin/users');
    }

    /**
     * Confirm
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirm(Request $request)
    {
        // email
        if ($request->get('type') === 'email' && is_numeric($request->get('id'))) {
            $user = User::find($request->get('id'));

            if ($user) {
                $user->email_confirmed = (($user->email_confirmed) ? 0 : 1);
                $user->save();
            }
        }

        // phone
        if ($request->get('type') === 'phone' && is_numeric($request->get('id'))) {
            $user = User::find($request->get('id'));

            if ($user) {
                $user->enabled = (($user->phone_confirmed) ? 0 : 1);
                $user->save();
            }
        }

        // acount enabled
        if ($request->get('type') === 'account' && is_numeric($request->get('id'))) {
            $user = User::find($request->get('id'));

            if ($user) {
                $user->enabled = (($user->enabled) ? 0 : 1);
                $user->save();
            }
        }

        return redirect()->back();
    }

    /**
     * Delete data
     *
     * @param $id
     * @return mixed
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
