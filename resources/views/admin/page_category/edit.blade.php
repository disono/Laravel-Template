{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

@extends('admin.layouts.master')

@section('content')
     <div class="container-fluid shadow-sm p-3 bg-white">
        <div class="row">
            <div class="col">
                <h3>{{ $view_title }}</h3>
                <hr>
                @include('admin.page.menu')
                @include('admin.page_category.menu')
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 col-md-4">
                <form action="{{ route('admin.page.category.update') }}" method="post" v-on:submit.prevent="onFormPost">
                    {{ csrf_field() }}

                    <input type="hidden" value="{{ $category->id }}" name="id">

                    <div class="form-group">
                        <label for="name">Name <strong class="text-danger">*</strong></label>

                        <input id="name" type="text"
                               class="form-control{{ hasInputError($errors, 'name') }}"
                               name="name" value="{{ old('name', $category->name) }}" data-validate="required">

                        @if ($errors->has('name'))
                            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="slug">Slug (Friendly URL) <strong class="text-danger">*</strong></label>

                        <input id="slug" type="text"
                               class="form-control{{ hasInputError($errors, 'slug') }}"
                               name="slug" value="{{ old('slug', $category->slug) }}" data-validate="required">

                        @if ($errors->has('slug'))
                            <div class="invalid-feedback">{{ $errors->first('slug') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="parent_id">Parent Category</label>

                        <select class="form-control" id="parent_id" name="parent_id">
                            <option value="">Select Parent Category</option>
                            @foreach($categories as $sub)
                                <option value="{{ $sub->id }}" {{ frmIsSelected('parent_id', $sub->id, $category->parent_id) }}>{{ $sub->name }}</option>
                            @endforeach
                        </select>

                        @if ($errors->has('parent_id'))
                            <div class="invalid-feedback">{{ $errors->first('parent_id') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>

                        <textarea name="description" id="description" rows="4"
                                  class="form-control{{ hasInputError($errors, 'name') }}">{{ old('description', $category->description) }}</textarea>

                        @if ($errors->has('name'))
                            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                        @endif
                    </div>

                    <button class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection