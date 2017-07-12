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
                <h3>Pages
                    <a href="{{url('admin/page/create')}}" class="btn btn-primary pull-right">Create New Page</a>
                </h3>

                {{-- search options --}}
                <div class="app-container">
                    <form action="" method="get" role="form" class="form-inline">
                        <div class="row row-con">
                            <div class="col-sm-12 col-md-3 form-group">
                                <input type="text" class="form-control" name="search" id="search"
                                       value="{{$request->get('search')}}" placeholder="Keyword">
                            </div>

                            <div class="col-sm-12 col-md-3 form-group">
                                <select class="form-control" name="page_category_id">
                                    <option value="">Category</option>
                                    @foreach($page_categories as $row)
                                        <option value="{{$row->id}}" {{ ($request->get('page_category_id') == $row->id) ? 'selected' : '' }}>
                                            {{$row->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-12 col-md-3 form-group">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </div>
                    </form>
                </div>

                @if(count($pages))
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody class="app-container">
                        @foreach($pages as $row)
                            <tr>
                                <td>{{$row->id}}</td>
                                <td>{{$row->name}}</td>
                                <td>{{$row->slug}}</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default btn-xs dropdown-toggle"
                                                data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                            Action <span class="caret"></span>
                                        </button>

                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="{{url('admin/page/edit/' . $row->id)}}">Edit</a>
                                            </li>
                                            <li><a href="{{url('admin/page/destroy/' . $row->id)}}"
                                                   class="delete-data">Delete</a></li>
                                        </ul>
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
@endsection