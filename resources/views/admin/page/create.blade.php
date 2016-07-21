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
                <div class="admin-container">
                    <h3 class="page-header">Create Page</h3>

                    <form action="{{url('admin/page/store')}}" method="post" role="form" enctype="multipart/form-data">
                        {{csrf_field()}}

                        <div class="row">
                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }} col-xs-12 col-md-6">
                                <label for="name">Name*</label>
                                <input type="text" class="form-control" name="name" id="name"
                                       placeholder="Name" value="{{old('name')}}">

                                @if ($errors->has('name'))
                                    <span class="help-block">{{ $errors->first('name') }}</span>
                                @endif
                            </div>

                            <div class="form-group{{ $errors->has('page_category_id') ? ' has-error' : '' }} col-xs-12 col-md-6">
                                <label for="page_category_id">Category*</label>
                                <select name="page_category_id" id="page_category_id" class="form-control">
                                    <option value="">Select Page Category</option>
                                    @foreach($page_categories as $row)
                                        <option value="{{$row->id}}" {{is_selected(old('page_category_id'), $row->id)}}>{{$row->name}}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('page_category_id'))
                                    <span class="help-block">{{ $errors->first('page_category_id') }}</span>
                                @endif
                            </div>

                            <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }} col-xs-12 col-md-6">
                                <label for="slug">Slug*</label>
                                <input type="text" class="form-control" name="slug" id="slug"
                                       placeholder="Slug" value="{{old('slug')}}">

                                @if ($errors->has('slug'))
                                    <span class="help-block">{{ $errors->first('slug') }}</span>
                                @endif
                            </div>

                            <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }} col-xs-12 col-md-6">
                                <label for="image">Cover(Optional)</label>
                                <input type="file" class="form-control" name="image" id="image">

                                @if ($errors->has('image'))
                                    <span class="help-block">{{ $errors->first('image') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
                            <label for="content">Content*</label>
                            <textarea name="content" id="content" class="form-control" cols="30" rows="10"
                                      placeholder="Content">{!! old('content') !!}</textarea>

                            @if ($errors->has('content'))
                                <span class="help-block">{{ $errors->first('content') }}</span>
                            @endif
                        </div>

                        <h4 class="page-header">Optional</h4>
                        <div class="row">
                            <div class="form-group{{ $errors->has('start_date') ? ' has-error' : '' }} col-xs-12 col-md-6">
                                <label for="start_date">Start Date</label>
                                <input type="text" class="form-control date-picker-min" name="start_date" value="{{old('start_date')}}">

                                @if ($errors->has('start_date'))
                                    <span class="help-block">{{ $errors->first('start_date') }}</span>
                                @endif
                            </div>

                            <div class="form-group{{ $errors->has('start_time') ? ' has-error' : '' }} col-xs-12 col-md-6">
                                <label for="start_time">Start Time</label>
                                <input type="text" class="form-control time-picker" name="start_time" value="{{old('start_time')}}">

                                @if ($errors->has('start_time'))
                                    <span class="help-block">{{ $errors->first('start_time') }}</span>
                                @endif
                            </div>

                            <div class="form-group{{ $errors->has('end_date') ? ' has-error' : '' }} col-xs-12 col-md-6">
                                <label for="end_date">End Date</label>
                                <input type="text" class="form-control date-picker-min" name="end_date" value="{{old('end_date')}}">

                                @if ($errors->has('end_date'))
                                    <span class="help-block">{{ $errors->first('end_date') }}</span>
                                @endif
                            </div>

                            <div class="form-group{{ $errors->has('end_time') ? ' has-error' : '' }} col-xs-12 col-md-6">
                                <label for="end_time">End Time</label>
                                <input type="text" class="form-control time-picker" name="end_time" value="{{old('end_time')}}">

                                @if ($errors->has('end_time'))
                                    <span class="help-block">{{ $errors->first('end_time') }}</span>
                                @endif
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.3.13/tinymce.min.js"></script>
    <script src="{{asset('assets/js/admin-page.js') . url_ext()}}"></script>
@endsection