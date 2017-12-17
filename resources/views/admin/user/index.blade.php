{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template
 * License: Apache 2.0
--}}
@extends('admin.layout.master')

@section('title', $title)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h3>Users</h3>

                {{-- search options --}}
                <form action="" method="get" role="form" class="mt-3">
                    <div class="form-row">
                        <div class="col">
                            <input type="text" class="form-control" name="search" id="search"
                                   value="{{ $request->get('search') }}" placeholder="Keyword">
                        </div>

                        <div class="col">
                            <select class="form-control" name="enabled">
                                <option value="">Enabled/Disabled Account</option>
                                <option value="1" {{ ($request->get('enabled') == '1') ? 'selected' : '' }}>
                                    Enabled
                                </option>
                                <option value="0" {{ ($request->get('enabled') == '0') ? 'selected' : '' }}>
                                    Disabled
                                </option>
                            </select>
                        </div>

                        <div class="col">
                            <select class="form-control" name="is_activated">
                                <option value="">Active/InActive Account</option>
                                <option value="1" {{ ($request->get('is_activated') == '1') ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="0" {{ ($request->get('is_activated') == '0') ? 'selected' : '' }}>
                                    InActive
                                </option>
                            </select>
                        </div>

                        <div class="col">
                            <select class="form-control" name="email_confirmed">
                                <option value="">Confirmed Email</option>
                                <option value="1" {{ ($request->get('email_confirmed') == '1') ? 'selected' : '' }}>
                                    Yes
                                </option>
                                <option value="0" {{ ($request->get('email_confirmed') == '0') ? 'selected' : '' }}>
                                    No
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row mt-3">
                        <div class="col">
                            <select class="form-control" name="country_id">
                                <option value="">Country</option>
                                @foreach($countries as $row)
                                    <option value="{{ $row->id }}" {{ ($request->get('country_id') == $row->id) ? 'selected' : '' }}>
                                        {{ $row->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col">
                            <select class="form-control" name="role">
                                <option value="">Role</option>
                                @foreach($role as $row)
                                    <option value="{{$row->slug}}" {{ ($request->get('role') == $row->slug) ? 'selected' : '' }}>
                                        {{$row->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </form>

                {{-- list of users --}}
                <div class="row mt-3">
                    <div class="col-12">
                        @if(count($users))
                            <table class="table table-hover table-responsive">
                                <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Country</th>
                                    <th>Account enabled</th>
                                    <th>Account Active</th>
                                    <th>Email confirmed</th>
                                    <th>Role</th>
                                    <th>Action</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($users as $row)
                                    <tr id="parent_tr_{{$row->id}}">
                                        <td>{{$row->id}}</td>
                                        <td>{{str_limit($row->full_name, 18)}}</td>
                                        <td>{{$row->email}}</td>
                                        <td>{{$row->country->name}}</td>
                                        <td>
                                            <a href="{{url('admin/user/confirm?type=' . 'account' . '&id=' . $row->id)}}">{{($row->enabled) ? 'Yes' : 'No'}}</a>
                                        </td>
                                        <td>
                                            <a href="{{url('admin/user/confirm?type=' . 'is_activated' . '&id=' . $row->id)}}">{{($row->is_activated) ? 'Deactivate' : 'Activate'}}</a>
                                        </td>
                                        <td>
                                            <a href="{{url('admin/user/confirm?type=' . 'email' . '&id=' . $row->id)}}">{{($row->email_confirmed) ? 'Yes' : 'No'}}</a>
                                        </td>
                                        <td>{{$row->role}}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle" type="button"
                                                        id="dropDownMenuButton" data-toggle="dropdown"
                                                        aria-haspopup="true"
                                                        aria-expanded="false">
                                                    Action
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropDownMenuButton">
                                                    <a class="dropdown-item"
                                                       href="{{url('admin/user/edit/' . $row->id)}}">Edit</a>
                                                    <a class="dropdown-item"
                                                       href="{{url('admin/user/password/edit/' . $row->id)}}">Reset
                                                        Password</a>
                                                    <a class="dropdown-item"
                                                       href="{{url('admin/user/login/' . $row->id)}}">Login</a>
                                                    <a class="dropdown-item"
                                                       href="{{url('admin/authorization/histories?user_id=' . $row->id)}}">Authorization
                                                        History</a>
                                                    <a class="dropdown-item"
                                                       href="{{url('admin/user/destroy/' . $row->id)}}"
                                                       v-on:click.prevent="onDeleteResource($event, '#parent_tr_{{$row->id}}')">Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            {{$users->appends($request->all())->render()}}
                        @else
                            <h1 class="text-center">No User.</h1>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection