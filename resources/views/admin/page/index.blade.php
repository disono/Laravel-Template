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
                <h3 class="page-header">Pages</h3>

                {{-- search options --}}
                <div class="admin-container">
                    <form action="" method="get" role="form" class="form-inline">
                        <div class="form-group">
                            <input type="text" class="form-control" name="search" id="search"
                                   value="{{$request->get('search')}}" placeholder="Keyword">
                        </div>

                        <div class="form-group">
                            <select class="form-control" name="page_category_id">
                                <option value="">Category</option>
                                @foreach($page_categories as $row)
                                    <option value="{{$row->id}}" {{ ($request->get('page_category_id') == $row->id) ? 'selected' : '' }}>
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

                <div class="admin-container">
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
                            <tbody>
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
    </div>

    @include('modals.delete')
@endsection