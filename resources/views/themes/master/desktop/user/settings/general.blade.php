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
                    <form role="form" method="POST" action="{{ route('module.user.setting.general.update') }}"
                          enctype="multipart/form-data" v-on:submit.prevent="onFormUpload">
                        {!! csrf_field() !!}

                        {{-- image/avatar --}}
                        <div class="text-center">
                            <img src="{{ $user->profile_picture }}" alt="{{ $user->first_name }}"
                                 style="height: 164px; width: 164px;" id="img_profile"
                                 class="mb-3 rounded-circle shadow-sm"
                                 v-on:click="onImageSelect('#profile_picture', '#img_profile')">

                            <div class="form-group">
                                <label for="profile_picture" class="d-none">Profile Picture</label>
                                <input type="file" name="profile_picture"
                                       accept="image/*"
                                       id="profile_picture"
                                       class="form-control {{ $errors->has('first_name') ? ' is-invalid' : '' }} d-none">

                                @if ($errors->has('profile_picture'))
                                    <div class="invalid-feedback">{{ $errors->first('profile_picture') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 col-sm-12 mb-3">
                                <label for="first_name">First Name <strong class="text-danger">*</strong></label>
                                <input type="text"
                                       class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}"
                                       name="first_name"
                                       value="{{ old('first_name', $user->first_name) }}"
                                       id="first_name" data-validate="required">

                                @if ($errors->has('first_name'))
                                    <div class="invalid-feedback">{{ $errors->first('first_name') }}</div>
                                @endif
                            </div>

                            <div class="col-md-4 col-sm-12 mb-3">
                                <label for="middle_name">Middle Name</label>
                                <input type="text"
                                       class="form-control{{ $errors->has('middle_name') ? ' is-invalid' : '' }}"
                                       name="middle_name"
                                       value="{{ old('middle_name', $user->middle_name) }}"
                                       id="middle_name">

                                @if ($errors->has('middle_name'))
                                    <div class="invalid-feedback">{{ $errors->first('middle_name') }}</div>
                                @endif
                            </div>

                            <div class="col-md-4 col-sm-12 mb-3">
                                <label for="last_name">Last Name <strong class="text-danger">*</strong></label>
                                <input type="text"
                                       class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}"
                                       name="last_name"
                                       value="{{ old('last_name', $user->last_name) }}"
                                       id="last_name" data-validate="required">

                                @if ($errors->has('last_name'))
                                    <div class="invalid-feedback">{{ $errors->first('last_name') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-sm-12 mb-3">
                                <label for="phone">Phone</label>
                                <input type="text"
                                       class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                       name="phone"
                                       value="{{ old('phone', $user->phone) }}"
                                       id="phone">

                                @if ($errors->has('phone'))
                                    <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                                @endif
                            </div>

                            <div class="col-md-6 col-sm-12 mb-3">
                                <label for="gender">Gender <strong class="text-danger">*</strong></label>
                                <select class="form-control select_picker{{ $errors->has('gender') ? ' is-invalid' : '' }}"
                                        data-style="btn-blue-50"
                                        name="gender" id="gender" data-validate="required">
                                    <option value="">Select Gender</option>
                                    <option value="Male" {{ (old('gender', $user->gender) == 'Male') ? 'selected' : '' }}>
                                        Male
                                    </option>
                                    <option value="Female" {{ (old('gender', $user->gender) == 'Female') ? 'selected' : '' }}>
                                        Female
                                    </option>
                                </select>

                                @if ($errors->has('gender'))
                                    <div class="invalid-feedback">{{ $errors->first('gender') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-sm-12 mb-3">
                                <label for="birthday">Birthday <strong class="text-danger">*</strong></label>
                                <input type="text"
                                       class="form-control date-picker-no-future{{ $errors->has('birthday') ? ' is-invalid' : '' }}"
                                       name="birthday"
                                       value="{{ old('birthday', $user->birthday) }}"
                                       id="birthday" data-validate="required">

                                @if ($errors->has('birthday'))
                                    <div class="invalid-feedback">{{ $errors->first('birthday') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 col-sm-12 mb-3">
                                <label for="address">Home Address</label>
                                <textarea
                                        class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}"
                                        name="address"
                                        rows="4"
                                        id="address">{{ old('address', $user->address) }}</textarea>

                                @if ($errors->has('address'))
                                    <div class="invalid-feedback">{{ $errors->first('address') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-sm-12 mb-3">
                                <label for="country_id">Country</label>
                                <select class="form-control select_picker{{ $errors->has('country_id') ? ' is-invalid' : '' }}"
                                        data-live-search="true"
                                        data-style="btn-blue-50"
                                        name="country_id" id="country_id"
                                        data-input-country-id="{{ $user->country_id }}"
                                        v-model="location.country_id"
                                        @change="onCountrySelect($event, location.country_id)">
                                    <option value="">Select Country</option>

                                    @foreach($countries as $row)
                                        <option value="{{$row->id}}"
                                                {{ (old('country_id', $user->country_id) == $row->id) ? 'selected' : '' }}>
                                            {{$row->name}}
                                        </option>
                                    @endforeach
                                </select>

                                @if ($errors->has('country_id'))
                                    <div class="invalid-feedback">{{ $errors->first('country_id') }}</div>
                                @endif
                            </div>

                            <div class="col-md-6 col-sm-12 mb-3">
                                <label for="city_id">City</label>
                                <select class="form-control select_picker{{ $errors->has('city_id') ? ' is-invalid' : '' }}"
                                        data-live-search="true"
                                        data-style="btn-blue-50"
                                        name="city_id" id="city_id">
                                    <option value="">Select City</option>

                                    @foreach($cities(['country_id' => $user->country_id]) as $city)
                                        <option value="{{ $city->id }}" {{ frmIsSelected('city_id', $city->id, $user->city_id) }}>
                                            {{ $city->name }}
                                        </option>
                                    @endforeach

                                    <option v-for="city in location.cities"
                                            v-bind:value="city.id">@{{ city.name }}
                                    </option>
                                </select>

                                @if ($errors->has('city_id'))
                                    <div class="invalid-feedback">{{ $errors->first('city_id') }}</div>
                                @endif
                            </div>
                        </div>

                        <hr>
                        <button type="submit" class="btn btn-primary pull-right">
                            Update Profile
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection