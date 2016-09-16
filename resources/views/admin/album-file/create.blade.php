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
            <div class="col-xs-12 col-md-6">
                <div class="admin-container">
                    <h3 class="page-header">Upload Photos to {{str_limit($album->name, 12)}}</h3>

                    <form action="{{url('admin/album/upload/store')}}" method="post" role="form"
                          enctype="multipart/form-data">
                        {{csrf_field()}}
                        <input type="hidden" value="{{$album->id}}" name="album_id">

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name">Upload Image*</label>
                            <input type="file" id="image" name="image[]" class="form-control" multiple="multiple">

                            @if ($errors->has('image'))
                                <span class="help-block">{{ $errors->first('image') }}</span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                            <label for="title">Title*</label>
                            <input type="text" class="form-control" name="title" id="title" placeholder="Title">

                            @if ($errors->has('title'))
                                <span class="help-block">{{ $errors->first('title') }}</span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control" cols="30" rows="10"
                                      placeholder="Description"></textarea>

                            @if ($errors->has('description'))
                                <span class="help-block">{{ $errors->first('description') }}</span>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary">Upload</button>
                    </form>
                </div>
            </div>

            <div class="col-xs-12 col-md-6">
                <div class="admin-container">
                    <h3 class="page-header">Images</h3>

                    @if($album->count_images)
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

                            <tbody>
                            @foreach($album->images as $row)
                                <tr>
                                    <td>{{$row->id}}</td>
                                    <th>
                                        <img src="{{$row->path}}" alt="{{$row->title}}"
                                             style="max-width: 50px !important;">
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
                    @else
                        <h1 class="text-center">No Images.</h1>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('modals.delete')
@endsection