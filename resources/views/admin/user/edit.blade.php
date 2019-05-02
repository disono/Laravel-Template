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

                <div class="row mt-3">
                    <div class="col">
                        <div class="text-center">
                            <img src="{{ $user->profile_picture }}" alt="{{ $user->first_name }}"
                                 style="height: 164px; width: 164px;"
                                 class="mb-3 rounded-circle shadow-sm">
                        </div>

                        <form action="{{ route('admin.user.update') }}" method="post"
                              v-on:submit.prevent="onFormUpload">
                            <input type="hidden" name="id" value="{{ $user->id }}">

                            <div class="row">
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <h5><i class="fas fa-user"></i> Profile</h5>
                                    <hr>

                                    <div class="form-group">
                                        <label for="profile_picture">Profile Image</label>

                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="profile_picture"
                                                   id="profile_picture">
                                            <label class="custom-file-label" for="profile_picture">Choose image</label>
                                        </div>

                                        @if ($errors->has('profile_picture'))
                                            <div class="invalid-feedback">{{ $errors->first('profile_picture') }}</div>
                                        @endif
                                    </div>

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

                                        <select class="custom-select{{ hasInputError($errors, 'gender') }}"
                                                name="gender" id="gender" data-validate="required">
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
                                               value="{{ old('birthday', humanDate($user->birthday, true)) }}"
                                               data-validate="required">

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

                                        <select class="custom-select" name="country_id" id="country_id"
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

                                        <select class="custom-select" name="city_id" id="city_id">
                                            <option value="">Select City</option>

                                            @foreach(\App\Models\City::fetchAll(['country_id' => $user->country_id]) as $city)
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
                                </div>

                                <div class="col-md-4 col-sm-12 mb-3">
                                    <h5><i class="fas fa-key"></i> Security</h5>
                                    <hr>

                                    <div class="form-group">
                                        <label for="role_id">Role <strong class="text-danger">*</strong></label>

                                        <select class="custom-select{{ hasInputError($errors, 'role_id') }}"
                                                name="role_id" id="role_id" data-validate="required">
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
                                               data-validate="required">

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
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="is_email_verified"
                                                   value="1"
                                                   name="is_email_verified" {{ frmIsChecked('is_email_verified', 1, $user->is_email_verified) }}>
                                            <label class="custom-control-label" for="is_email_verified">Email
                                                Verified</label>
                                        </div>

                                        @if ($errors->has('is_email_verified'))
                                            <div class="invalid-feedback">{{ $errors->first('is_email_verified') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="is_phone_verified"
                                                   value="1"
                                                   name="is_phone_verified" {{ frmIsChecked('is_phone_verified', 1, $user->is_phone_verified) }}>
                                            <label class="custom-control-label" for="is_phone_verified">Phone
                                                Verified</label>
                                        </div>

                                        @if ($errors->has('is_phone_verified'))
                                            <div class="invalid-feedback">{{ $errors->first('is_phone_verified') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input"
                                                   id="is_account_activated"
                                                   value="1"
                                                   name="is_account_activated" {{ frmIsChecked('is_account_activated', 1, $user->is_account_activated) }}>
                                            <label class="custom-control-label" for="is_account_activated">
                                                Account Activated
                                            </label>
                                        </div>

                                        @if ($errors->has('is_account_activated'))
                                            <div class="invalid-feedback">{{ $errors->first('is_account_activated') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="is_account_enabled"
                                                   value="1"
                                                   name="is_account_enabled" {{ frmIsChecked('is_account_enabled', 1, $user->is_account_enabled) }}>
                                            <label class="custom-control-label" for="is_account_enabled">
                                                Account Enabled
                                            </label>
                                        </div>

                                        @if ($errors->has('is_account_enabled'))
                                            <div class="invalid-feedback">{{ $errors->first('is_account_enabled') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection