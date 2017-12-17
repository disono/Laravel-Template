{{--
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
--}}
@extends('admin.layout.master')

@section('title', $title)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h3>Albums
                    <a href="{{url('admin/album/create')}}" class="btn btn-primary pull-right">Create Album</a>
                </h3>

                <div class="row mt-3">
                    <div class="col-12">
                        @if(count($albums))
                            <table class="table table-hover table-responsive">
                                <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($albums as $row)
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
                                                       href="{{url('admin/album/edit/' . $row->id)}}">Edit</a>
                                                    <a class="dropdown-item"
                                                       href="{{url('admin/album/upload/create/' . $row->id)}}">Upload
                                                        Photos</a>
                                                    <a class="dropdown-item"
                                                       href="{{url('admin/album/destroy/' . $row->id)}}"
                                                       v-on:click.prevent="onDeleteResource($event, '#parent_tr_{{$row->id}}')">Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            {{$albums->appends($request->all())->render()}}
                        @else
                            <h1 class="text-center">No albums.</h1>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection