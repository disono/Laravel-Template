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
                @include('admin.user.menu')

                <form action="{{ route('admin.user.store') }}" method="post" v-on:submit.prevent="onFormUpload">
                    <div class="row mt-3">
                        <div class="col">
                            <div class="text-center">
                                <img src="/assets/img/placeholders/profile_picture.png" alt="Profile Picture"
                                     style="height: 164px; width: 164px;" id="img_profile"
                                     class="mb-3 rounded-circle shadow-sm"
                                     v-on:click="imgSelect('#profile_picture', '#img_profile')">

                                <div class="form-group">
                                    <label for="profile_picture" class="d-none">Profile Picture</label>
                                    <input type="file" name="profile_picture"
                                           accept="image/*"
                                           id="profile_picture" class="form-control is-invalid d-none">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <h5><i class="fas fa-user"></i> Profile</h5>
                                    <hr>

                                    <div class="form-group">
                                        <label for="first_name">First Name <strong
                                                    class="text-danger">*</strong></label>

                                        <input id="first_name" type="text"
                                               class="form-control{{ hasInputError($errors, 'first_name') }}"
                                               name="first_name" value="{{ old('first_name') }}"
                                               data-validate="required">

                                        @if ($errors->has('first_name'))
                                            <div class="invalid-feedback">{{ $errors->first('first_name') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="middle_name">Middle Name</label>

                                        <input id="middle_name" type="text"
                                               class="form-control{{ hasInputError($errors, 'middle_name') }}"
                                               name="middle_name" value="{{ old('middle_name') }}">

                                        @if ($errors->has('middle_name'))
                                            <div class="invalid-feedback">{{ $errors->first('middle_name') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="last_name">Last Name <strong class="text-danger">*</strong></label>

                                        <input id="last_name" type="text"
                                               class="form-control{{ hasInputError($errors, 'last_name') }}"
                                               name="last_name" value="{{ old('last_name') }}" data-validate="required">

                                        @if ($errors->has('last_name'))
                                            <div class="invalid-feedback">{{ $errors->first('last_name') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="gender">Gender <strong class="text-danger">*</strong></label>

                                        <select class="form-control select_picker{{ hasInputError($errors, 'gender') }}"
                                                name="gender" id="gender" data-validate="required">
                                            <option value="">Select Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>

                                        @if ($errors->has('gender'))
                                            <div class="invalid-feedback">{{ $errors->first('gender') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="birthday">Birthday <strong class="text-danger">*</strong></label>

                                        <input id="birthday" type="text"
                                               class="form-control date-picker-no-future{{ hasInputError($errors, 'birthday') }}"
                                               name="birthday" value="{{ old('birthday') }}" data-validate="required">

                                        @if ($errors->has('birthday'))
                                            <div class="invalid-feedback">{{ $errors->first('birthday') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12 mb-3">
                                    <h5><i class="fas fa-building"></i> Location</h5>
                                    <hr>

                                    <div class="form-group">
                                        <label for="address">Address <strong class="text-danger">*</strong></label>

                                        <textarea name="address" id="address"
                                                  class="form-control{{ hasInputError($errors, 'address') }}"
                                                  data-validate="required"></textarea>

                                        @if ($errors->has('address'))
                                            <div class="invalid-feedback">{{ $errors->first('address') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="postal_code">Postal Code <strong
                                                    class="text-danger">*</strong></label>

                                        <input id="postal_code" type="text"
                                               class="form-control{{ hasInputError($errors, 'postal_code') }}"
                                               name="postal_code" value="{{ old('postal_code') }}"
                                               data-validate="required">

                                        @if ($errors->has('postal_code'))
                                            <div class="invalid-feedback">{{ $errors->first('postal_code') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="country_id">Country</label>

                                        <select class="form-control select_picker" name="country_id" id="country_id"
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
                                        <label for="city_id">City</label>

                                        <select class="form-control select_picker" data-live-search="true"
                                                name="city_id" id="city_id">
                                            <option value="">Select City</option>
                                            <option v-for="city in location.cities" v-bind:value="city.id">@{{ city.name
                                                }}
                                            </option>
                                        </select>

                                        @if ($errors->has('city_id'))
                                            <div class="invalid-feedback">{{ $errors->first('city_id') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12 mb-3">
                                    <h5><i class="fas fa-key"></i> Security</h5>
                                    <hr>

                                    <div class="form-group">
                                        <label for="role_id">Role <strong class="text-danger">*</strong></label>

                                        <select class="form-control select_picker{{ hasInputError($errors, 'role_id') }}"
                                                name="role_id" id="role_id" data-validate="required">
                                            <option value="">Select Role</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>

                                        @if ($errors->has('role_id'))
                                            <div class="invalid-feedback">{{ $errors->first('role_id') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="username">Username <strong class="text-danger">*</strong></label>

                                        <input id="username" type="text"
                                               class="form-control{{ hasInputError($errors, 'username') }}"
                                               name="username" value="{{ old('username') }}" data-validate="required">

                                        @if ($errors->has('username'))
                                            <div class="invalid-feedback">{{ $errors->first('username') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Email <strong class="text-danger">*</strong></label>

                                        <input id="email" type="text"
                                               class="form-control{{ hasInputError($errors, 'email') }}"
                                               name="email" value="{{ old('email') }}" data-validate="required">

                                        @if ($errors->has('email'))
                                            <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="password">Password <strong class="text-danger">*</strong></label>

                                        <input id="password" type="text"
                                               class="form-control{{ hasInputError($errors, 'password') }}"
                                               name="password" value="{{ old('password') }}" data-validate="required">

                                        @if ($errors->has('password'))
                                            <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label class="custom-control material-checkbox">
                                            <input type="checkbox" class="material-control-input"
                                                   value="1"
                                                   name="is_email_verified" {{ frmIsChecked('is_email_verified', 1) }}>
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
                                                   name="is_phone_verified" {{ frmIsChecked('is_phone_verified', 1) }}>
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
                                                   name="is_account_activated" {{ frmIsChecked('is_account_activated', 1) }}>
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
                                                   name="is_account_enabled" {{ frmIsChecked('is_account_enabled', 1) }}>
                                            <span class="material-control-indicator"></span>
                                            <span class="material-control-description">Account Enabled</span>
                                        </label>

                                        @if ($errors->has('is_account_enabled'))
                                            <div class="invalid-feedback">{{ $errors->first('is_account_enabled') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-raised btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection