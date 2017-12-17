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
            <div class="col">
                <h3>Upload Photos to {{str_limit($album->name, 12)}}</h3>

                <form action="{{url('admin/album/upload/store')}}" method="post" role="form"
                      enctype="multipart/form-data">
                    {{csrf_field()}}
                    <input type="hidden" value="{{$album->id}}" name="album_id">

                    <div class="form-group">
                        <label for="name">Upload Image*</label>
                        <input type="file" id="image" name="image[]"
                               class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" multiple="multiple">

                        @if ($errors->has('image'))
                            <span class="invalid-feedback">{{ $errors->first('image') }}</span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="title">Title*</label>
                        <input type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                               name="title" id="title" placeholder="Title">

                        @if ($errors->has('title'))
                            <span class="invalid-feedback">{{ $errors->first('title') }}</span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description"
                                  class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" rows="4"
                                  placeholder="Description"></textarea>

                        @if ($errors->has('description'))
                            <span class="invalid-feedback">{{ $errors->first('description') }}</span>
                        @endif
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>

            <div class="col">
                <h3>Images</h3>

                @if($album->count_images)
                    <table class="table table-hover table-responsive">
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
                        @foreach($album->images as $row)
                            <tr id="parent_tr_{{$row->id}}">
                                <td>{{$row->id}}</td>
                                <th>
                                    <img src="{{$row->path}}" alt="{{$row->title}}"
                                         style="max-width: 50px !important;">
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
                @else
                    <h1 class="text-center">No Images.</h1>
                @endif
            </div>
        </div>
    </div>
@endsection