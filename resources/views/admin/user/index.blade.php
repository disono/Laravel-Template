{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
--}}
@extends('admin.layout.master')

@section('title', $title)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <h3 class="page-header">Users</h3>

                {{-- search options --}}
                <div class="app-container">
                    <form action="" method="get" role="form" class="form-inline">
                        <div class="form-group">
                            <input type="text" class="form-control" name="search" id="search"
                                   value="{{$request->get('search')}}" placeholder="Keyword">
                        </div>

                        <div class="form-group">
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

                        <div class="form-group">
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

                        <div class="form-group">
                            <select class="form-control" name="country_id">
                                <option value="">Country</option>
                                @foreach($countries as $row)
                                    <option value="{{$row->id}}" {{ ($request->get('country_id') == $row->id) ? 'selected' : '' }}>
                                        {{$row->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <select class="form-control" name="role">
                                <option value="">Role</option>
                                @foreach($role as $row)
                                    <option value="{{$row->slug}}" {{ ($request->get('role') == $row->slug) ? 'selected' : '' }}>
                                        {{$row->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                </div>

                {{-- list of users --}}
                <div class="app-container">
                    @if(count($users))
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Country</th>
                                <th>Account enabled</th>
                                <th>Email confirmed</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($users as $row)
                                <tr>
                                    <td>{{$row->id}}</td>
                                    <td>{{str_limit($row->full_name, 18)}}</td>
                                    <td>{{$row->email}}</td>
                                    <td>{{$row->country->name}}</td>
                                    <td>
                                        <a href="{{url('admin/user/confirm?type=' . 'account' . '&id=' . $row->id)}}">{{($row->enabled) ? 'Yes' : 'No'}}</a>
                                    </td>
                                    <td>
                                        <a href="{{url('admin/user/confirm?type=' . 'email' . '&id=' . $row->id)}}">{{($row->email_confirmed) ? 'Yes' : 'No'}}</a>
                                    </td>
                                    <td>{{$row->role}}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-xs dropdown-toggle"
                                                    data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                Options <span class="caret"></span>
                                            </button>

                                            <ul class="dropdown-menu">
                                                <li><a href="{{url('admin/user/edit/' . $row->id)}}">Edit</a></li>
                                                <li><a href="{{url('admin/user/password/edit/' . $row->id)}}">Reset
                                                        Password</a></li>
                                                <li role="separator" class="divider"></li>
                                                <li><a href="{{url('admin/user/login/' . $row->id)}}">Login</a></li>
                                                <li>
                                                    <a href="{{url('admin/authorization/histories?user_id=' . $row->id)}}">Authorization
                                                        History</a></li>
                                                <li role="separator" class="divider"></li>
                                                <li><a href="{{url('admin/user/destroy/' . $row->id)}}"
                                                       class="delete-data">Delete</a></li>
                                            </ul>
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
@endsection