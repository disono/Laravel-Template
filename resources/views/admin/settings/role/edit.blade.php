{{--
 * @author      Archie, Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @lincense    https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

@extends('admin.layouts.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="row">
                    <div class="col">
                        <h1 class="header">{{ $view_title }}</h1>

                        @include('admin.settings.menu')
                        @include('admin.settings.role.menu')
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col">
                        <form action="{{ route('admin.role.update') }}" method="post"
                              v-on:submit.prevent="onFormUpload">
                            {{ csrf_field() }}
                            <input type="hidden" value="{{ $role->id }}" name="id">

                            <div class="row">
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <label for="name">Name <strong class="text-danger">*</strong></label>

                                    <input id="name" type="text"
                                           class="form-control{{ hasInputError($errors, 'name') }}"
                                           name="name" value="{{ old('name', $role->name) }}" data-validate="required">

                                    @if ($errors->has('name'))
                                        <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>

                                <div class="col-md-4 col-sm-12 mb-3">
                                    <label for="slug">Slug <strong class="text-danger">*</strong></label>

                                    <input id="slug" type="text"
                                           class="form-control{{ hasInputError($errors, 'slug') }}"
                                           name="slug" value="{{ old('slug', $role->slug) }}" data-validate="required">

                                    @if ($errors->has('slug'))
                                        <div class="invalid-feedback">{{ $errors->first('slug') }}</div>
                                    @endif
                                </div>

                                <div class="col-md-4 col-sm-12 mb-3">
                                    <label for="description">Description</label>

                                    <input id="description" type="text"
                                           class="form-control{{ hasInputError($errors, 'description') }}"
                                           name="description" value="{{ old('description', $role->description) }}">

                                    @if ($errors->has('description'))
                                        <div class="invalid-feedback">{{ $errors->first('description') }}</div>
                                    @endif
                                </div>
                            </div>

                            <button type="submit" class="btn btn-raised btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection