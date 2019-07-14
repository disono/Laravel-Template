{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

@extends(currentTheme() . 'layouts.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-lg-3">
                @includeTheme('user.settings.menu')
            </div>

            <div class="col-sm-12 col-lg-9">
                <div class="p-3 shadow-sm rounded bg-white border-0">
                    <h3>{{ $view_title }}</h3>
                    <hr>

                    <form action="{{ route('module.user.setting.address.store') }}" method="post"
                          v-on:submit.prevent="onFormUpload">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="address">Complete Address <strong class="text-danger">*</strong></label>

                            <textarea name="address" id="address" class="form-control"
                                      data-validate="required"></textarea>

                            @if ($errors->has('address'))
                                <div class="invalid-feedback">{{ $errors->first('address') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="postal_code">Postal Code <strong class="text-danger">*</strong></label>

                            <input id="postal_code" type="text"
                                   class="form-control{{ hasInputError($errors, 'postal_code') }}"
                                   name="postal_code" value="{{ old('postal_code') }}" data-validate="required">

                            @if ($errors->has('postal_code'))
                                <div class="invalid-feedback">{{ $errors->first('postal_code') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="country_id">Country <strong class="text-danger">*</strong></label>

                            <select name="country_id" id="country_id" class="form-control select_picker"
                                    data-style="btn-blue-50"
                                    data-validate="required"
                                    data-live-search="true"
                                    v-model="location.country_id"
                                    @change="onCountrySelect($event, location.country_id)">
                                <option value="">Select Country</option>

                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('country_id'))
                                <div class="invalid-feedback">{{ $errors->first('country_id') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="city_id">City <strong class="text-danger">*</strong></label>

                            <select name="city_id" id="city_id" class="form-control select_picker"
                                    data-style="btn-blue-50"
                                    data-live-search="true"
                                    data-validate="required">
                                <option value="">Select City</option>
                                <option v-for="city in location.cities" v-bind:value="city.id">@{{ city.name }}</option>
                            </select>

                            @if ($errors->has('city_id'))
                                <div class="invalid-feedback">{{ $errors->first('city_id') }}</div>
                            @endif
                        </div>

                        <hr>
                        <button type="submit" class="btn btn-raised btn-primary">Create new address</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection