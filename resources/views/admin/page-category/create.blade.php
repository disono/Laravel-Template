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
                <h3>Create Page Category</h3>

                <form action="{{url('admin/page-category/store')}}" method="post" role="form">
                    {{csrf_field()}}

                    <div class="form-group">
                        <label for="name">Name*</label>
                        <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                               name="name" id="name" placeholder="Name" value="{{old('name')}}">

                        @if ($errors->has('name'))
                            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="slug">Slug* (Slugs make the URL more user-friendly)</label>
                        <input type="text" class="form-control{{ $errors->has('slug') ? ' is-invalid' : '' }}"
                               name="slug" id="slug"
                               placeholder="Slug" value="{{old('slug')}}">

                        @if ($errors->has('slug'))
                            <div class="invalid-feedback">{{ $errors->first('slug') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description"
                                  class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" rows="4"
                                  placeholder="Description">{{old('description')}}</textarea>

                        @if ($errors->has('description'))
                            <div class="invalid-feedback">{{ $errors->first('description') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="parent_id">Parent Category</label>
                        <select name="parent_id" id="parent_id"
                                class="form-control{{ $errors->has('parent_id') ? ' is-invalid' : '' }}">
                            <option value="">Select parent category</option>
                            @foreach(\App\Models\PageCategory::nestedToTabs() as $row)
                                <option value="{{$row->id}}" {{is_selected(old('parent_id'), $row->id)}}>{{$row->name}}</option>
                            @endforeach
                        </select>

                        @if ($errors->has('parent_id'))
                            <div class="invalid-feedback">{{ $errors->first('parent_id') }}</div>
                        @endif
                    </div>

                    <div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input{{ $errors->has('is_link') ? ' is-invalid' : '' }}"
                                   type="checkbox" value="1" name="is_link">
                            Link
                        </label>

                        @if ($errors->has('is_link'))
                            <div class="invalid-feedback">{{ $errors->first('is_link') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="external_link">External Link</label>
                        <input type="text" class="form-control{{ $errors->has('external_link') ? ' is-invalid' : '' }}"
                               name="external_link" id="external_link" placeholder="External Link"
                               value="{{old('external_link')}}">

                        @if ($errors->has('external_link'))
                            <div class="invalid-feedback">{{ $errors->first('external_link') }}</div>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Create</button>
                </form>
            </div>
        </div>
    </div>
@endsection