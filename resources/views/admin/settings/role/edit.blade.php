{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

@extends('admin.layouts.master')

@section('content')
    <h3 class="mb-3 font-weight-bold">{{ $view_title }}</h3>

    <div class="container-fluid shadow-sm p-3 bg-white">
        <div class="row">
            <div class="col">
                <div class="row">
                    <div class="col">
                        @include('admin.settings.menu')
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-sm-12 col-md-4">
                        <form action="{{ route('admin.role.update') }}" method="post"
                              v-on:submit.prevent="onFormUpload">
                            {{ csrf_field() }}
                            <input type="hidden" value="{{ $role->id }}" name="id">

                            <div class="form-group">
                                <label for="name">Name <strong class="text-danger">*</strong></label>

                                <input id="name" type="text"
                                       class="form-control{{ hasInputError($errors, 'name') }}"
                                       name="name" value="{{ old('name', $role->name) }}" data-validate="required">

                                @if ($errors->has('name'))
                                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="slug">Slug <strong class="text-danger">*</strong></label>

                                <input id="slug" type="text"
                                       class="form-control{{ hasInputError($errors, 'slug') }}"
                                       name="slug" value="{{ old('slug', $role->slug) }}" data-validate="required">

                                @if ($errors->has('slug'))
                                    <div class="invalid-feedback">{{ $errors->first('slug') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>

                                <input id="description" type="text"
                                       class="form-control{{ hasInputError($errors, 'description') }}"
                                       name="description" value="{{ old('description', $role->description) }}">

                                @if ($errors->has('description'))
                                    <div class="invalid-feedback">{{ $errors->first('description') }}</div>
                                @endif
                            </div>

                            <hr>
                            <button type="submit" class="btn btn-raised btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection