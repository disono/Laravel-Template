{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

@extends('admin.layouts.master')

@section('content')
    <div class="container-fluid shadow-sm p-3 bg-white">
        <div class="row">
            <div class="col">
                <h3>{{ $view_title }}</h3>
                <hr>
                @include('admin.user.menu')
            </div>
        </div>

        <div class="row mt-3">
            <div class="col">
                <form action="{{ route('admin.user.list') }}" method="get" class="mb-3" id="frmTableFilter">
                    <input type="submit" style="display: none;">

                    @include('vendor.app.toolbar', ['csvSource' => 'users', 'createRoute' => 'admin.user.create', 'toolbarHasDel' => true])

                    <div class="table-responsive-sm">
                        <table class="table table-bordered">
                            <thead class="table-borderless">
                            <tr>
                                {!! thDelete() !!}

                                <th>#</th>
                                <th><input type="text" class="form-control form-control-sm" name="full_name"
                                           placeholder="Name" value="{{ $request->get('full_name') }}"></th>
                                <th><input type="email" class="form-control form-control-sm" name="email"
                                           placeholder="Email" value="{{ $request->get('email') }}"></th>
                                <th><input type="text" class="form-control form-control-sm" name="username"
                                           placeholder="Username" value="{{ $request->get('username') }}"></th>
                                <th>
                                    <select class="form-control form-control-sm select_picker"
                                            name="role_id" data-style="btn-gray"
                                            @change="onSelectChangeSubmitForm($event, '#frmTableFilter')">
                                        <option value="">Role (All)</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ frmIsSelected('role_id', $role->id) }}>{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </th>
                                <th>
                                    <select class="form-control form-control-sm select_picker"
                                            name="is_email_verified" data-style="btn-gray"
                                            @change="onSelectChangeSubmitForm($event, '#frmTableFilter')">
                                        <option value="">Email Verified (All)</option>
                                        <option value="1" {{ frmIsSelected('is_email_verified', 1) }}>Yes</option>
                                        <option value="0" {{ frmIsSelected('is_email_verified', 0) }}>No</option>
                                    </select>
                                </th>
                                <th>
                                    <select class="form-control form-control-sm select_picker"
                                            name="is_account_activated" data-style="btn-gray"
                                            @change="onSelectChangeSubmitForm($event, '#frmTableFilter')">
                                        <option value="">Account Activated (All)</option>
                                        <option value="1" {{ frmIsSelected('is_account_activated', 1) }}>Yes</option>
                                        <option value="0" {{ frmIsSelected('is_account_activated', 0) }}>No</option>
                                    </select>
                                </th>
                                <th>
                                    <select class="form-control form-control-sm select_picker"
                                            name="is_account_enabled" data-style="btn-gray"
                                            @change="onSelectChangeSubmitForm($event, '#frmTableFilter')">
                                        <option value="">Account Enabled (All)</option>
                                        <option value="1" {{ frmIsSelected('is_account_enabled', 1) }}>Yes</option>
                                        <option value="0" {{ frmIsSelected('is_account_enabled', 0) }}>No</option>
                                    </select>
                                </th>
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($users as $row)
                                <tr id="parent_tr_{{ $row->id }}">
                                    {!! tdDelete($row->id) !!}

                                    <td>{{ $row->id }}</td>
                                    <td>{{ $row->full_name }}</td>
                                    <td>{{ $row->email }}</td>
                                    <td>{{ $row->username }}</td>
                                    <td>{{ $row->role }}</td>
                                    <td>
                                        <a href="{{ url('admin/user/update/' . $row->id . '/is_email_verified/' . (($row->is_email_verified) ? 0 : 1)) }}">
                                            {{ ($row->is_email_verified) ? 'YES' : 'NO' }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ url('admin/user/update/' . $row->id . '/is_account_activated/' . (($row->is_account_activated) ? 0 : 1)) }}">
                                            {{ ($row->is_account_activated) ? 'YES' : 'NO' }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ url('admin/user/update/' . $row->id . '/is_account_enabled/' . (($row->is_account_enabled) ? 0 : 1)) }}">
                                            {{ ($row->is_account_enabled) ? 'YES' : 'NO' }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-light btn-sm dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                <i class="fas fa-cog"></i>
                                            </button>

                                            <div class="dropdown-menu">
                                                <a class="dropdown-item"
                                                   href="{{ url('admin/user/edit/' . $row->id) }}">Edit</a>

                                                <a class="dropdown-item"
                                                   href="{{ route('admin.user.authentication.history', ['user_id' => $row->id]) }}">
                                                    Authentication Histories
                                                </a>

                                                <div class="dropdown-divider"></div>

                                                <a class="dropdown-item"
                                                   href="{{ url('admin/user/destroy/' . $row->id) }}"
                                                   id="parent_tr_del_{{ $row->id }}"
                                                   v-on:click.prevent="onDeleteResource($event, '#parent_tr_{{ $row->id }}')">Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>

                @include('vendor.app.pagination', ['_lists' => $users])
            </div>
        </div>
    </div>
@endsection