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
                <h3>Pages
                    <a href="{{url('admin/page/create')}}" class="btn btn-primary pull-right">Create New Page</a>
                </h3>

                {{-- search options --}}
                <form action="" method="get" class="mt-3">
                    <div class="form-row">
                        <div class="col">
                            <input type="text" class="form-control" name="search" id="search"
                                   value="{{$request->get('search')}}" placeholder="Keyword">
                        </div>

                        <div class="col">
                            <select class="form-control" name="page_category_id">
                                <option value="">Category</option>
                                @foreach($page_categories as $row)
                                    <option value="{{$row->id}}" {{ ($request->get('page_category_id') == $row->id) ? 'selected' : '' }}>
                                        {{$row->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col">
                            <button type="submit" class="btn btn-primary btn-block">Search</button>
                        </div>
                    </div>
                </form>

                <div class="row mt-3">
                    <div class="col-12">
                        @if(count($pages))
                            <table class="table table-hover">
                                <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Action</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($pages as $row)
                                    <tr id="parent_tr_{{$row->id}}">
                                        <td>{{$row->id}}</td>
                                        <td>{{$row->name}}</td>
                                        <td>{{$row->category_name}}</td>
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
                                                       href="{{url('admin/page/edit/' . $row->id)}}">Edit</a>
                                                    <a class="dropdown-item"
                                                       href="{{url('admin/page/destroy/' . $row->id)}}"
                                                       v-on:click.prevent="onDeleteResource($event, '#parent_tr_{{$row->id}}')">Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            {{$pages->appends($request->all())->render()}}
                        @else
                            <h1 class="text-center">No pages.</h1>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection