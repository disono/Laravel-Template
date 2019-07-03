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
                @include('admin.user.menu')

                <div class="row mt-3">
                    <div class="col">
                        <form action="{{ route('admin.user.update') }}" method="post"
                              v-on:submit.prevent="onFormUpload">
                            <input type="hidden" name="id" value="{{ $user->id }}">

                            <div class="text-center">
                                <img src="{{ $user->profile_picture }}" alt="{{ $user->first_name }}"
                                     style="height: 164px; width: 164px;" id="img_profile"
                                     class="mb-3 rounded-circle shadow-sm"
                                     v-on:click="onImageSelect('#profile_picture', '#img_profile')">

                                <div class="form-group">
                                    <label for="profile_picture" class="d-none">Profile Picture</label>
                                    <input type="file" name="profile_picture"
                                           accept="image/*"
                                           id="profile_picture" class="form-control is-invalid d-none">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <h5 class="font-weight-bold"><i class="fas fa-user"></i> Personal Information</h5>
                                    <hr>

                                    <div class="form-group">
                                        <label for="first_name">First Name <strong
                                                    class="text-danger">*</strong></label>

                                        <input id="first_name" type="text"
                                               class="form-control{{ hasInputError($errors, 'first_name') }}"
                                               name="first_name" value="{{ old('first_name', $user->first_name) }}"
                                               data-validate="required">

                                        @if ($errors->has('first_name'))
                                            <div class="invalid-feedback">{{ $errors->first('first_name') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="middle_name">Middle Name</label>

                                        <input id="middle_name" type="text"
                                               class="form-control{{ hasInputError($errors, 'middle_name') }}"
                                               name="middle_name" value="{{ old('middle_name', $user->middle_name) }}">

                                        @if ($errors->has('middle_name'))
                                            <div class="invalid-feedback">{{ $errors->first('middle_name') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="last_name">Last Name <strong class="text-danger">*</strong></label>

                                        <input id="last_name" type="text"
                                               class="form-control{{ hasInputError($errors, 'last_name') }}"
                                               name="last_name" value="{{ old('last_name', $user->last_name) }}"
                                               data-validate="required">

                                        @if ($errors->has('last_name'))
                                            <div class="invalid-feedback">{{ $errors->first('last_name') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="gender">Gender <strong class="text-danger">*</strong></label>

                                        <select class="form-control select_picker{{ hasInputError($errors, 'gender') }}"
                                                data-style="btn-blue-50"
                                                name="gender" id="gender"
                                                data-validate="required">
                                            <option value="">Select Gender</option>
                                            <option value="Male" {{ frmIsSelected('gender', 'Male', $user->gender) }}>
                                                Male
                                            </option>
                                            <option value="Female" {{ frmIsSelected('gender', 'Female', $user->gender) }}>
                                                Female
                                            </option>
                                        </select>

                                        @if ($errors->has('gender'))
                                            <div class="invalid-feedback">{{ $errors->first('gender') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="birthday">Birthday <strong class="text-danger">*</strong></label>

                                        <input id="birthday" type="text"
                                               class="form-control date-picker-no-future{{ hasInputError($errors, 'birthday') }}"
                                               name="birthday"
                                               value="{{ old('birthday', $user->formatted_birthday) }}"
                                               data-validate="required">

                                        @if ($errors->has('birthday'))
                                            <div class="invalid-feedback">{{ $errors->first('birthday') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12 mb-3">
                                    <h5 class="font-weight-bold"><i class="fas fa-building"></i> Contact Information</h5>
                                    <hr>

                                    <div class="form-group">
                                        <label for="address">Address <strong class="text-danger">*</strong></label>

                                        <textarea name="address" id="address"
                                                  class="form-control{{ hasInputError($errors, 'address') }}"
                                                  data-validate="required">{{ old('address', $user->address) }}</textarea>

                                        @if ($errors->has('address'))
                                            <div class="invalid-feedback">{{ $errors->first('address') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="postal_code">Postal Code <strong
                                                    class="text-danger">*</strong></label>

                                        <input id="postal_code" type="text"
                                               class="form-control{{ hasInputError($errors, 'postal_code') }}"
                                               name="postal_code" value="{{ old('postal_code', $user->postal_code) }}"
                                               data-validate="required">

                                        @if ($errors->has('postal_code'))
                                            <div class="invalid-feedback">{{ $errors->first('postal_code') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="country_id">Country</label>

                                        <select class="form-control select_picker" name="country_id" id="country_id"
                                                data-style="btn-blue-50"
                                                data-live-search="true"
                                                data-input-country-id="{{ $user->country_id }}"
                                                v-model="location.country_id"
                                                @change="onCountrySelect($event, location.country_id)">
                                            <option value="">Select Country</option>

                                            @foreach($countries as $country)
                                                <option value="{{ $country->id }}"
                                                        {{ frmIsSelected('country_id', $country->id, $user->country_id) }}>
                                                    {{ $country->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @if ($errors->has('country_id'))
                                            <div class="invalid-feedback">{{ $errors->first('country_id') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="city_id">City</label>

                                        <select class="form-control select_picker"
                                                data-style="btn-blue-50"
                                                data-live-search="true"
                                                name="city_id" id="city_id">
                                            <option value="">Select City</option>

                                            @foreach((new \App\Models\City())->fetchAll(['country_id' => $user->country_id]) as $city)
                                                <option value="{{ $city->id }}"
                                                        {{ frmIsSelected('city_id', $city->id, $user->city_id) }}>
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

                                    <div class="form-group">
                                        <label for="phone">Phone Number</label>

                                        <input id="phone" type="text"
                                               class="form-control{{ hasInputError($errors, 'phone') }}"
                                               name="phone" value="{{ old('phone', $user->phone) }}">

                                        @if ($errors->has('phone'))
                                            <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12 mb-3">
                                    <h5 class="font-weight-bold"><i class="fas fa-key"></i> Account Information</h5>
                                    <hr>

                                    <div class="form-group">
                                        <label for="role_id">Role <strong class="text-danger">*</strong></label>

                                        <select class="form-control select_picker{{ hasInputError($errors, 'role_id') }}"
                                                data-style="btn-blue-50"
                                                name="role_id" id="role_id"
                                                data-validate="required">
                                            <option value="">Select Role</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}" {{ frmIsSelected('role_id', $role->id, $user->role_id) }}>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @if ($errors->has('role_id'))
                                            <div class="invalid-feedback">{{ $errors->first('role_id') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="username">Username</label>

                                        <input id="username" type="text"
                                               class="form-control{{ hasInputError($errors, 'username') }}"
                                               name="username" value="{{ old('username', $user->username) }}"
                                               data-validate="required">

                                        @if ($errors->has('username'))
                                            <div class="invalid-feedback">{{ $errors->first('username') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Email <strong class="text-danger">*</strong></label>

                                        <input id="email" type="text"
                                               class="form-control{{ hasInputError($errors, 'email') }}"
                                               name="email" value="{{ old('email', $user->email) }}"
                                               data-validate="required|email">

                                        @if ($errors->has('email'))
                                            <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="password">Password</label>

                                        <input id="password" type="text"
                                               class="form-control{{ hasInputError($errors, 'password') }}"
                                               name="password" value="{{ old('password') }}">

                                        @if ($errors->has('password'))
                                            <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label class="custom-control material-checkbox">
                                            <input type="checkbox" class="material-control-input"
                                                   value="1"
                                                   name="is_email_verified" {{ frmIsChecked('is_email_verified', 1, $user->is_email_verified) }}>
                                            <span class="material-control-indicator"></span>
                                            <span class="material-control-description">Email Verified</span>
                                        </label>

                                        @if ($errors->has('is_email_verified'))
                                            <div class="invalid-feedback">{{ $errors->first('is_email_verified') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label class="custom-control material-checkbox">
                                            <input type="checkbox" class="material-control-input"
                                                   value="1"
                                                   name="is_phone_verified" {{ frmIsChecked('is_phone_verified', 1, $user->is_phone_verified) }}>
                                            <span class="material-control-indicator"></span>
                                            <span class="material-control-description">Phone Verified</span>
                                        </label>

                                        @if ($errors->has('is_phone_verified'))
                                            <div class="invalid-feedback">{{ $errors->first('is_phone_verified') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label class="custom-control material-checkbox">
                                            <input type="checkbox" class="material-control-input"
                                                   value="1"
                                                   name="is_account_activated" {{ frmIsChecked('is_account_activated', 1, $user->is_account_activated) }}>
                                            <span class="material-control-indicator"></span>
                                            <span class="material-control-description">Account Activated</span>
                                        </label>

                                        @if ($errors->has('is_account_activated'))
                                            <div class="invalid-feedback">{{ $errors->first('is_account_activated') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label class="custom-control material-checkbox">
                                            <input type="checkbox" class="material-control-input"
                                                   value="1"
                                                   name="is_account_enabled" {{ frmIsChecked('is_account_enabled', 1, $user->is_account_enabled) }}>
                                            <span class="material-control-indicator"></span>
                                            <span class="material-control-description">Account Enabled</span>
                                        </label>

                                        @if ($errors->has('is_account_enabled'))
                                            <div class="invalid-feedback">{{ $errors->first('is_account_enabled') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection