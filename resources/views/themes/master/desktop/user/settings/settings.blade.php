{{--
 * @author      Archie, Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @lincense    https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

@extends(currentTheme() . 'layouts.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-lg-3">
                @include(currentTheme() . 'user.settings.menu')
            </div>

            <div class="col-sm-12 col-lg-9">
                <form role="form" method="POST" action="{{ route('user.settingsUpdate') }}"
                      enctype="multipart/form-data" v-on:submit.prevent="onFormUpload">
                    {!! csrf_field() !!}

                    {{-- image/avatar --}}
                    <img src="{{ $user->profile_picture }}" alt="{{ $user->first_name }}"
                         class="rounded mx-auto d-block img-thumbnail mb-3 my-3"
                         style="width: 140px;">

                    <div class="row">
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="first_name">First Name</label>
                            <input type="text"
                                   class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}"
                                   name="first_name"
                                   value="{{ old('first_name', $user->first_name) }}"
                                   id="first_name" data-validate="required">

                            @if ($errors->has('first_name'))
                                <div class="invalid-feedback">{{ $errors->first('first_name') }}</div>
                            @endif
                        </div>

                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="last_name">Last Name</label>
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
                            <label for="profile_picture">Profile Image</label>
                            <div class="custom-file">
                                <input type="file" id="profile_picture" name="profile_picture"
                                       class="custom-file-input{{ $errors->has('profile_picture') ? ' is-invalid' : '' }}">
                                <label for="profile_picture" class="custom-file-label">Choose image</label>
                            </div>

                            @if ($errors->has('profile_picture'))
                                <div class="invalid-feedback">{{ $errors->first('profile_picture') }}</div>
                            @endif
                        </div>

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
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="gender">Gender</label>
                            <select class="form-control{{ $errors->has('gender') ? ' is-invalid' : '' }}"
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

                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="birthday">Birthday</label>
                            <input type="text"
                                   class="form-control date-picker{{ $errors->has('birthday') ? ' is-invalid' : '' }}"
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
                            <label for="address">Address</label>
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
                            <select class="form-control{{ $errors->has('country_id') ? ' is-invalid' : '' }}"
                                    name="country_id" id="country_id">
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
                            <select class="form-control{{ $errors->has('city_id') ? ' is-invalid' : '' }}"
                                    name="city_id" id="city_id">
                                <option value="">Select City</option>
                                @foreach($cities as $row)
                                    <option value="{{$row->id}}"
                                            {{ (old('city_id', $user->city_id) == $row->id) ? 'selected' : '' }}>
                                        {{$row->name}}
                                    </option>
                                @endforeach
                            </select>

                            @if ($errors->has('city_id'))
                                <div class="invalid-feedback">{{ $errors->first('city_id') }}</div>
                            @endif
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary pull-right">
                        Save Changes
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection