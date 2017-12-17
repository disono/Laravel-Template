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
                <h3>Images</h3>

                <div class="row mt-3">
                    <div class="col-12">
                        @if(count($images))
                            <table class="table table-hover">
                                <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Uploaded by</th>
                                    <th>Title</th>
                                    <th>Action</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($images as $row)
                                    <tr id="parent_tr_{{$row->id}}">
                                        <td>{{$row->id}}</td>
                                        <th>
                                            <a href="{{$row->path}}" data-toggle="light-box"
                                               data-title="{{$row->title}}">
                                                <img src="{{$row->path}}" alt="{{$row->title}}"
                                                     style="max-width: 50px !important;"></a>
                                        </th>
                                        <td>{{$row->full_name}}</td>
                                        <td>{{$row->title}}</td>
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
                                                       href="{{url('admin/image/destroy/' . $row->id)}}"
                                                       v-on:click.prevent="onDeleteResource($event, '#parent_tr_{{$row->id}}')">Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            {{$images->appends($request->all())->render()}}
                        @else
                            <h1 class="text-center">No images uploaded.</h1>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection