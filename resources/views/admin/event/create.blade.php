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
                <div class="app-container">
                    <h3 class="page-header">Create Event</h3>

                    <form action="{{url('admin/event/store')}}" method="post" role="form" enctype="multipart/form-data">
                        {{csrf_field()}}

                        <div class="row">
                            <div class="col-xs-12 col-md-9">
                                <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
                                    <label for="content">Content*</label>
                                    <textarea name="content" id="content" class="form-control" cols="10" rows="22"
                                              placeholder="Content">{!! old('content') !!}</textarea>

                                    @if ($errors->has('content'))
                                        <span class="help-block">{{ $errors->first('content') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-xs-12 col-md-3">
                                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                    <label for="name">Name*</label>
                                    <input type="text" class="form-control" name="name" id="name"
                                           placeholder="Name" value="{{old('name')}}">

                                    @if ($errors->has('name'))
                                        <span class="help-block">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }}">
                                    <label for="slug">Slug* (Slugs make the URL more user-friendly)</label>
                                    <input type="text" class="form-control" name="slug" id="slug"
                                           placeholder="Slug" value="{{old('slug')}}">

                                    @if ($errors->has('slug'))
                                        <span class="help-block">{{ $errors->first('slug') }}</span>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                                    <label for="image">Cover(Optional)</label>
                                    <input type="file" class="form-control" name="image" id="image">

                                    @if ($errors->has('image'))
                                        <span class="help-block">{{ $errors->first('image') }}</span>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('template') ? ' has-error' : '' }}">
                                    <label for="template">Template(Optional)</label>
                                    <input type="text" class="form-control" name="template" id="template"
                                           placeholder="Template filename" value="{{old('template')}}">

                                    @if ($errors->has('template'))
                                        <span class="help-block">{{ $errors->first('template') }}</span>
                                    @endif
                                </div>

                                <div class="form-group {{ $errors->has('draft') ? ' has-error' : '' }}">
                                    <div class="checkbox">
                                        <input type="checkbox" id="draft" name="draft" value="1">
                                        <label for="draft">
                                            Save as draft
                                        </label>
                                    </div>

                                    @if ($errors->has('draft'))
                                        <span class="help-block">{{ $errors->first('draft') }}</span>
                                    @endif
                                </div>

                                <button type="submit" class="btn btn-primary">Create</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('vendor.loaders', ['scripts' => [
    'https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.5.2/tinymce.min.js',
    'assets/js/tiny-mce-init.js'
]])