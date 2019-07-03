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
                    <h3>
                        {{ $view_title }}
                        @if(__settings('addressVerification')->value === 'enabled')
                            @if($address->is_verified)
                                <span class="badge badge-success font-weight-light font-size-sm">Verified</span>
                            @else
                                <span class="badge badge-danger font-weight-light font-size-sm">Unverified</span>
                            @endif
                        @endif
                    </h3>
                    <hr>

                    <form action="{{ route('user.setting.address.update') }}" method="post"
                          v-on:submit.prevent="onFormUpload">
                        {{ csrf_field() }}

                        <input type="hidden" value="{{ $address->id }}" name="id">

                        <div class="form-group">
                            <label for="address">Complete Address <strong class="text-danger">*</strong></label>

                            <textarea name="address" id="address" class="form-control"
                                      data-validate="required">{{ $address->address }}</textarea>

                            @if ($errors->has('address'))
                                <div class="invalid-feedback">{{ $errors->first('address') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="postal_code">Postal Code <strong class="text-danger">*</strong></label>

                            <input id="postal_code" type="text"
                                   class="form-control{{ hasInputError($errors, 'postal_code') }}"
                                   name="postal_code" value="{{ old('postal_code', $address->postal_code) }}"
                                   data-validate="required">

                            @if ($errors->has('postal_code'))
                                <div class="invalid-feedback">{{ $errors->first('postal_code') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="country_id">Country <strong class="text-danger">*</strong></label>

                            <select name="country_id" id="country_id"
                                    class="form-control select_picker{{ $errors->has('country_id') ? ' is-invalid' : '' }}"
                                    data-style="btn-blue-50"
                                    data-live-search="true"
                                    data-validate="required"
                                    data-input-country-id="{{ $address->country_id }}"
                                    v-model="location.country_id"
                                    @change="onCountrySelect($event, location.country_id)">
                                <option value="">Select Country</option>

                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}"
                                            {{ frmIsSelected('country_id', $country->id, $address->country_id) }}>{{ $country->name }}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('country_id'))
                                <div class="invalid-feedback">{{ $errors->first('country_id') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="city_id">City <strong class="text-danger">*</strong></label>

                            <select name="city_id" id="city_id"
                                    class="form-control select_picker{{ $errors->has('city_id') ? ' is-invalid' : '' }}"
                                    data-style="btn-blue-50"
                                    data-live-search="true"
                                    data-validate="required">
                                <option value="">Select City</option>

                                @foreach($cities(['country_id' => $address->country_id]) as $city)
                                    <option value="{{ $city->id }}"
                                            data-input-city-remove="true" {{ frmIsSelected('city_id', $city->id, $address->city_id) }}>
                                        {{ $city->name }}
                                    </option>
                                @endforeach

                                <option v-for="city in location.cities" v-bind:value="city.id">@{{ city.name }}</option>
                            </select>

                            @if ($errors->has('city_id'))
                                <div class="invalid-feedback">{{ $errors->first('city_id') }}</div>
                            @endif
                        </div>

                        @if(__settings('addressVerification')->value === 'enabled' && !$address->is_verified)
                            <div class="form-group">
                                <label for="verify_code">Verification Code</label>

                                <input id="verify_code" type="text"
                                       placeholder="Received verification code"
                                       class="form-control{{ hasInputError($errors, 'verify_code') }}"
                                       name="verify_code"
                                       value="{{ old('verify_code') }}">

                                @if ($errors->has('verify_code'))
                                    <div class="invalid-feedback">{{ $errors->first('verify_code') }}</div>
                                @endif
                            </div>
                        @endif

                        <hr>
                        <button type="submit" class="btn btn-raised btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection