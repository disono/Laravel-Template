{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template
 * License: Apache 2.0
--}}
@extends(current_theme() . 'layout.master')

@section('title', $title)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @include(current_theme() . 'menu.settings')

                <div class="row mt-3">
                    <div class="col-6 offset-md-3">
                        <h3>General Account Settings</h3>

                        <form role="form" method="POST" action="{{ url('user/update/setting') }}"
                              enctype="multipart/form-data">
                            {!! csrf_field() !!}

                            {{-- image/avatar --}}
                            <img src="{{$user->avatar}}" alt="{{$user->first_name}}"
                                 class="rounded mx-auto d-block img-thumbnail"
                                 style="width: 180px;">

                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="first_name">First Name</label>
                                        <input type="text"
                                               class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}"
                                               name="first_name"
                                               value="{{ old('first_name', $user->first_name) }}"
                                               id="first_name">

                                        @if ($errors->has('first_name'))
                                            <div class="invalid-feedback">{{ $errors->first('first_name') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="last_name">Last Name</label>
                                        <input type="text"
                                               class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}"
                                               name="last_name"
                                               value="{{ old('last_name', $user->last_name) }}"
                                               id="last_name">

                                        @if ($errors->has('last_name'))
                                            <div class="invalid-feedback">{{ $errors->first('last_name') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="image">Upload Image/Avatar</label>
                                        <input type="file" id="image" name="image"
                                               class="form-control{{ $errors->has('image') ? ' is-invalid' : '' }}">

                                        @if ($errors->has('image'))
                                            <div class="invalid-feedback">{{ $errors->first('image') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="form-group">
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
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="gender">Gender</label>
                                        <select class="form-control{{ $errors->has('gender') ? ' is-invalid' : '' }}"
                                                name="gender" id="gender">
                                            <option value="">Select Gender</option>
                                            <option value="Male" {{ ($user->gender == 'Male') ? 'selected' : '' }}>
                                                Male
                                            </option>
                                            <option value="Female" {{ ($user->gender == 'Female') ? 'selected' : '' }}>
                                                Female
                                            </option>
                                        </select>

                                        @if ($errors->has('gender'))
                                            <div class="invalid-feedback">{{ $errors->first('gender') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="form-group">
                                        <label for="birthday">Birthday</label>
                                        <input type="text"
                                               class="form-control date-picker{{ $errors->has('birthday') ? ' is-invalid' : '' }}"
                                               name="birthday"
                                               value="{{ old('birthday', $user->birthday) }}"
                                               id="birthday">

                                        @if ($errors->has('birthday'))
                                            <div class="invalid-feedback">{{ $errors->first('birthday') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="about">About Me</label>
                                        <textarea class="form-control{{ $errors->has('about') ? ' is-invalid' : '' }}"
                                                  name="about"
                                                  rows="4"
                                                  id="about">{{ old('about', $user->about) }}</textarea>

                                        @if ($errors->has('about'))
                                            <div class="invalid-feedback">{{ $errors->first('about') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="form-group">
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
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="country_id">Country</label>
                                        <select class="form-control{{ $errors->has('country_id') ? ' is-invalid' : '' }}"
                                                name="country_id" id="country_id">
                                            <option value="">Select Country</option>
                                            @foreach($countries as $row)
                                                <option value="{{$row->id}}"
                                                        {{ (old('country_id') == $row->id || $user->country_id == $row->id) ? 'selected' : '' }}>
                                                    {{$row->name}}
                                                </option>
                                            @endforeach
                                        </select>

                                        @if ($errors->has('country_id'))
                                            <div class="invalid-feedback">{{ $errors->first('country_id') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-floppy-o"></i> Save Changes
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection