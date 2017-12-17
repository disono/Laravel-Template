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
                <h3>Roles</h3>

                <div class="row mt-3">
                    <div class="col-12">
                        @if(count($roles))
                            <table class="table table-hover">
                                <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Identifier</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($roles as $row)
                                    <tr id="parent_tr_{{$row->id}}">
                                        <td>{{$row->id}}</td>
                                        <td>{{$row->name}}</td>
                                        <td>{{$row->slug}}</td>
                                        <td>{{str_limit($row->description, 22)}}</td>
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
                                                       href="{{url('admin/role/edit/' . $row->id)}}">Edit</a>
                                                    <a class="dropdown-item"
                                                       href="{{url('admin/authorization-roles/' . $row->id)}}">Assign
                                                        Authorizations</a>
                                                    <a class="dropdown-item"
                                                       href="{{url('admin/role/destroy/' . $row->id)}}"
                                                       v-on:click.prevent="onDeleteResource($event, '#parent_tr_{{$row->id}}')">Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            {{$roles->appends($request->all())->render()}}
                        @else
                            <h4 class="text-center">No roles.</h4>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection