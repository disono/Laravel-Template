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
                <h3>
                    Authorizations

                    <a href="{{url('admin/authorization/reset')}}" class="pull-right btn btn-primary">Reset Authorizations</a>
                </h3>

                @if(count($authorizations))
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Identifier</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody class="app-container">
                        @foreach($authorizations as $row)
                            <tr>
                                <td>{{$row->id}}</td>
                                <td>{{$row->name}}</td>
                                <td>{{$row->identifier}}</td>
                                <td>{{str_limit($row->description, 22)}}</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default btn-xs dropdown-toggle"
                                                data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                            Action <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="{{url('admin/authorization/edit/' . $row->id)}}">Edit</a>
                                            </li>
                                            <li>
                                                <a href="{{url('admin/authorization/destroy/' . $row->id)}}"
                                                   class="delete-data">Delete</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    {{$authorizations->appends($request->all())->render()}}
                @else
                    <h1 class="text-center">No Authorization.</h1>
                @endif
            </div>
        </div>
    </div>
@endsection