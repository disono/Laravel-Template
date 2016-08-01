{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: www.webmons.com
 * License: Apache 2.0
--}}
@extends('admin.layout.master')

@section('title', $title)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="admin-container">
                    <h3 class="page-header">Assign Authorization Roles</h3>

                    <form action="{{url('admin/authorization-role/store')}}" method="post" role="form" class="form-inline">
                        {{csrf_field()}}
                        <input type="hidden" value="{{$role_id}}" name="role_id">

                        <div class="form-group">
                            <select name="authorization_id" id="authorization_id" class="form-control">
                                <option value="">Select Authorization</option>
                                @foreach($authorizations as $row)
                                    <option value="{{$row->id}}">{{$row->name}} ({{$row->identifier}})</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Assign</button>
                    </form>

                    <hr>

                    @if(count($authorization_roles))
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Authorization Name</th>
                                <th>Identifier</th>
                                <th>Role Description</th>
                                <th>Authorization Description</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($authorization_roles as $row)
                                <tr>
                                    <td>{{$row->id}}</td>
                                    <td>{{$row->authorization_name}}</td>
                                    <td>{{$row->authorization_identifier}}</td>
                                    <td>{{str_limit($row->role_description, 22)}}</td>
                                    <td>{{str_limit($row->authorization_description, 22)}}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-xs dropdown-toggle"
                                                    data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                Action <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="{{url('admin/authorization-role/destroy/' . $row->id)}}"
                                                       class="delete-data">Delete</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        {{$authorization_roles->appends($request->all())->render()}}
                    @else
                        <h1 class="text-center">No Assigned Authorization Role.</h1>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('modals.delete')
@endsection