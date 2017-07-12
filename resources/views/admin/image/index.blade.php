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
                <h3>Images</h3>

                @if(count($images))
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Uploaded by</th>
                            <th>Title</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody class="app-container">
                        @foreach($images as $row)
                            <tr>
                                <td>{{$row->id}}</td>
                                <th>
                                    <a href="{{$row->path}}" data-toggle="light-box" data-title="{{$row->title}}">
                                        <img src="{{$row->path}}" alt="{{$row->title}}"
                                             style="max-width: 50px !important;"></a>
                                </th>
                                <td>{{$row->full_name}}</td>
                                <td>{{$row->title}}</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default btn-xs dropdown-toggle"
                                                data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                            Options <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a href="{{url('admin/image/destroy/' . $row->id)}}"
                                                   class="delete-data">Delete</a></li>
                                        </ul>
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
@endsection