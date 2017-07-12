{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
--}}
@extends('admin.layout.master')

@section('title', $title)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <h3 class="page-header">Assign Authorization Roles</h3>

                <div class="app-container">
                    <form action="{{url('admin/authorization-roles/' . $role_id)}}" method="get" role="form"
                          class="form-inline">
                        <div class="row row-con">
                            <div class="col-sm-12 col-md-3 form-group">
                                <input type="text" class="form-control" name="search" id="search"
                                       value="{{$request->get('search')}}" placeholder="Keyword">
                            </div>

                            <div class="col-sm-12 col-md-3 form-group">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </div>
                    </form>
                </div>

                <form action="{{url('admin/authorization-role/store')}}" method="post" role="form">
                    {{csrf_field()}}
                    <input type="hidden" value="{{$role_id}}" name="role_id">

                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Authorization Name</th>
                            <th>Identifier/Access Routes</th>
                            <th>Authorization Description</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody class="app-container">
                        @foreach($authorization as $row)
                            <tr>
                                <td>{{$row->id}}</td>
                                <td>{{$row->name}}</td>
                                <td>{{$row->identifier}}</td>
                                <td>{{str_limit($row->description, 22)}}</td>
                                <td>
                                    <div class="checkbox">
                                        <input type="checkbox" id="role_{{$row->id}}" value="{{$row->id}}"
                                               name="authorization_id[]"
                                                {{is_checked($row->id, array_search_value(old('authorization_id', $authorization_roles), $row->id))}}>
                                        <label for="role_{{$row->id}}">
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <button class="btn btn-primary pull-right">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection