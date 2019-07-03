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
                        <form action="{{ route('admin.settingCountry.update') }}" method="post"
                              v-on:submit.prevent="onFormUpload">
                            {{ csrf_field() }}

                            <input type="hidden" name="id" value="{{ $country->id }}">

                            <div class="form-group">
                                <label for="code">Code <strong class="text-danger">*</strong></label>

                                <input id="code" type="text"
                                       class="form-control{{ hasInputError($errors, 'code') }}"
                                       name="code" value="{{ old('code', $country->code) }}" data-validate="required">

                                @if ($errors->has('code'))
                                    <div class="invalid-feedback">{{ $errors->first('code') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="name">Name <strong class="text-danger">*</strong></label>

                                <input id="name" type="text"
                                       class="form-control{{ hasInputError($errors, 'name') }}"
                                       name="name" value="{{ old('name', $country->name) }}" data-validate="required">

                                @if ($errors->has('name'))
                                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="lat">Lat</label>

                                <input id="lat" type="number"
                                       class="form-control{{ hasInputError($errors, 'lat') }}"
                                       data-validate="numeric"
                                       name="lat" value="{{ old('lat', $country->lat) }}">

                                @if ($errors->has('lat'))
                                    <div class="invalid-feedback">{{ $errors->first('lat') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="lng">Lng</label>

                                <input id="lng" type="number"
                                       class="form-control{{ hasInputError($errors, 'lng') }}"
                                       data-validate="numeric"
                                       name="lng" value="{{ old('lng', $country->lng) }}">

                                @if ($errors->has('lng'))
                                    <div class="invalid-feedback">{{ $errors->first('lng') }}</div>
                                @endif
                            </div>

                            <hr>
                            <button type="submit" class="btn btn-raised btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection