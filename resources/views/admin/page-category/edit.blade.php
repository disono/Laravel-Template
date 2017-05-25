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
            <div class="col-xs-12 col-md-6 col-xs-offset-0 col-md-offset-3">
                <div class="app-container">
                    <h3 class="page-header">Edit Page Category</h3>

                    <form action="{{url('admin/page-category/update')}}" method="post" role="form">
                        {{csrf_field()}}
                        <input type="hidden" value="{{$page_category->id}}" name="id">

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name">Name*</label>
                            <input type="text" class="form-control" name="name" id="name"
                                   value="{{$page_category->name}}"
                                   placeholder="Name">

                            @if ($errors->has('name'))
                                <span class="help-block">{{ $errors->first('name') }}</span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }}">
                            <label for="slug">Slug* (Slugs make the URL more user-friendly)</label>
                            <input type="text" class="form-control" name="slug" id="slug"
                                   value="{{$page_category->slug}}" placeholder="Slug">

                            @if ($errors->has('slug'))
                                <span class="help-block">{{ $errors->first('slug') }}</span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="4"
                                      placeholder="Description">{{$page_category->description}}</textarea>

                            @if ($errors->has('description'))
                                <span class="help-block">{{ $errors->first('description') }}</span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('parent_id') ? ' has-error' : '' }}">
                            <label for="parent_id">Parent Category</label>
                            <select name="parent_id" id="parent_id" class="form-control">
                                <option value="">Select parent category</option>
                                @foreach(\App\Models\PageCategory::nestedToTabs() as $row)
                                    <option value="{{$row->id}}" {{is_selected($row->id, $page_category->parent_id)}}>{{$row->name}}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('parent_id'))
                                <span class="help-block">{{ $errors->first('parent_id') }}</span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('is_link') ? ' has-error' : '' }}">
                            <div class="checkbox">
                                <input type="checkbox" id="is_link" name="is_link"
                                       value="1" {{($page_category->is_link) ? 'checked' : null}}>
                                <label for="is_link">
                                    Link
                                </label>
                            </div>

                            @if ($errors->has('is_link'))
                                <span class="help-block">{{ $errors->first('is_link') }}</span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('external_link') ? ' has-error' : '' }}">
                            <label for="external_link">External Link</label>
                            <input type="text" class="form-control" name="external_link"
                                   id="external_link" placeholder="External Link" value="{{$page_category->external_link}}">

                            @if ($errors->has('external_link'))
                                <span class="help-block">{{ $errors->first('external_link') }}</span>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save Changes
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection