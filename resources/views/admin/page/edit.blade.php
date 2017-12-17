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
                <h3 class="page-header">Create Page</h3>

                <form action="{{url('admin/page/update')}}" method="post" role="form" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <input type="hidden" name="id" value="{{ $page->id }}">

                    <div class="row">
                        <div class="col-12 col-sm-9">
                            <div class="form-group">
                                <label for="content">Content*</label>
                                <textarea name="content" id="content"
                                          class="form-control{{ $errors->has('content') ? ' is-invalid' : '' }}"
                                          placeholder="Content">{!! old('content', $page->content) !!}</textarea>

                                @if ($errors->has('content'))
                                    <div class="invalid-feedback">{{ $errors->first('content') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-12 col-sm-3">
                            <div class="form-group">
                                <label for="name">Name*</label>
                                <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                       name="name" id="name" placeholder="Name" value="{{old('name', $page->name)}}">

                                @if ($errors->has('name'))
                                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="page_category_id">Category*</label>
                                <select name="page_category_id" id="page_category_id"
                                        class="form-control{{ $errors->has('page_category_id') ? ' is-invalid' : '' }}">
                                    <option value="">Select Page Category</option>

                                    @foreach(\App\Models\PageCategory::nestedToTabs() as $row)
                                        <option value="{{$row->id}}" {{is_selected(old('page_category_id', $page->page_category_id), $row->id)}}>{{$row->name}}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('page_category_id'))
                                    <div class="invalid-feedback">{{ $errors->first('page_category_id') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="slug">Slug* (Slugs make the URL more user-friendly)</label>
                                <input type="text" class="form-control{{ $errors->has('slug') ? ' is-invalid' : '' }}"
                                       name="slug" id="slug"
                                       placeholder="Slug" value="{{old('slug', $page->slug)}}">

                                @if ($errors->has('slug'))
                                    <div class="invalid-feedback">{{ $errors->first('slug') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="image">Cover(Optional)</label>
                                <input type="file" class="form-control{{ $errors->has('image') ? ' is-invalid' : '' }}"
                                       name="image" id="image">

                                @if ($errors->has('image'))
                                    <div class="invalid-feedback">{{ $errors->first('image') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="template">Template(Optional)</label>
                                <input type="text"
                                       class="form-control{{ $errors->has('template') ? ' is-invalid' : '' }}"
                                       name="template" id="template" placeholder="Template filename"
                                       value="{{old('template', $page->template)}}">

                                @if ($errors->has('template'))
                                    <div class="invalid-feedback">{{ $errors->first('template') }}</div>
                                @endif
                            </div>

                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input is-invalid"
                                           type="checkbox" value="1"
                                           name="is_email_to_subscriber"
                                            {{is_checked(old('is_email_to_subscriber', $page->is_email_to_subscriber), 1)}}>
                                    Email to subscriber

                                    @if ($errors->has('is_email_to_subscriber'))
                                        <div class="invalid-feedback">{{ $errors->first('is_email_to_subscriber') }}</div>
                                    @endif
                                </label>
                            </div>

                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input is-invalid"
                                           type="checkbox" value="1"
                                           name="draft" {{is_checked(old('draft', $page->draft), 1)}}>
                                    Save as draft

                                    @if ($errors->has('draft'))
                                        <div class="invalid-feedback">{{ $errors->first('draft') }}</div>
                                    @endif
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-floppy-o"></i> Save
                                Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@include('vendor.loaders', ['scripts' => [
    asset('assets/js/lib/tinymce/tinymce.min.js')
]])