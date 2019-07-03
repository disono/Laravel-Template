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
                @include('admin.settings.menu')

                <div class="row mt-3">
                    <div class="col-sm-12 col-md-4">
                        <form action="{{ route('admin.settingCategory.update') }}" method="post"
                              v-on:submit.prevent="onFormUpload">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{ $setting_category->id }}">

                            <div class="form-group">
                                <label for="name">Name <strong class="text-danger">*</strong></label>

                                <input id="name" type="text"
                                       class="form-control{{ hasInputError($errors, 'name') }}"
                                       name="name" value="{{ old('name', $setting_category->name) }}"
                                       data-validate="required">

                                @if ($errors->has('name'))
                                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
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