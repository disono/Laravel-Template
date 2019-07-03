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
                        <form action="{{ route('admin.settingCity.update') }}" method="post"
                              v-on:submit.prevent="onFormUpload">
                            {{ csrf_field() }}

                            <input type="hidden" name="id" value="{{ $city->id }}">

                            <div class="form-group">
                                <input type="hidden" name="country_id" value="{{ $city->country_id }}">
                                <label for="country_name">Country <strong class="text-danger">*</strong></label>

                                <input id="country_name" type="text"
                                       class="form-control{{ hasInputError($errors, 'country_name') }}"
                                       name="country_name" value="{{ old('country_name', $city->country_name) }}"
                                       data-disabled="yes" disabled>

                                @if ($errors->has('country_id'))
                                    <div class="invalid-feedback">{{ $errors->first('country_id') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="name">Name <strong class="text-danger">*</strong></label>

                                <input id="name" type="text"
                                       class="form-control{{ hasInputError($errors, 'name') }}"
                                       name="name" value="{{ old('name', $city->name) }}" data-validate="required">

                                @if ($errors->has('name'))
                                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="lat">Lat</label>

                                <input id="lat" type="text"
                                       class="form-control{{ hasInputError($errors, 'lat') }}"
                                       data-validate="numeric"
                                       name="lat" value="{{ old('lat', $city->lat) }}">

                                @if ($errors->has('lat'))
                                    <div class="invalid-feedback">{{ $errors->first('lat') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="lng">Lng</label>

                                <input id="lng" type="text"
                                       class="form-control{{ hasInputError($errors, 'lng') }}"
                                       data-validate="numeric"
                                       name="lng" value="{{ old('lng', $city->lng) }}">

                                @if ($errors->has('lng'))
                                    <div class="invalid-feedback">{{ $errors->first('lng') }}</div>
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