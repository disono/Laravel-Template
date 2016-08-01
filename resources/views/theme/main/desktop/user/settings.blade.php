{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: www.webmons.com
 * License: Apache 2.0
--}}
@extends(current_theme() . 'layout.master')

@section('title', $title)

@section('content')
    <div class="container-fluid has-header">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                @include(current_theme() . 'menu.settings')

                <div class="row">
                    <div class="col-sm-12">
                        <h3>General Account Settings</h3>

                        <form class="form-horizontal" role="form" method="POST"
                              action="{{ url('user/update/setting') }}" enctype="multipart/form-data">
                            {!! csrf_field() !!}

                            {{-- image/avatar --}}
                            <div class="text-center">
                                <img src="{{$user->avatar}}" alt="{{$user->first_name}}" class="img-circle"
                                     style="width: 120px;">
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                                        <label class="col-md-3 control-label">Upload Image/Avatar</label>

                                        <div class="col-md-9">
                                            <input type="file" id="image" name="image" class="form-control">

                                            @if ($errors->has('image'))
                                                <span class="help-block">
                                                    {{ $errors->first('image') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                                        <label class="col-md-3 control-label">First Name</label>

                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="first_name"
                                                   value="{{ (old('first_name')) ? old('first_name') : $user->first_name }}">

                                            @if ($errors->has('first_name'))
                                                <span class="help-block">
                                                    {{ $errors->first('first_name') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                                        <label class="col-md-3 control-label">Last Name</label>

                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="last_name"
                                                   value="{{ (old('last_name')) ? old('last_name') : $user->last_name }}">

                                            @if ($errors->has('last_name'))
                                                <span class="help-block">
                                                    {{ $errors->first('last_name') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                                        <label class="col-md-3 control-label">Phone</label>

                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="phone"
                                                   value="{{ (old('phone')) ? old('phone') : $user->phone }}">

                                            @if ($errors->has('phone'))
                                                <span class="help-block">
                                                    {{ $errors->first('phone') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
                                        <label class="col-md-3 control-label">Gender</label>

                                        <div class="col-md-9">
                                            <select class="form-control col-md-12 col-xs-12" name="gender">
                                                <option value="">Select Gender</option>
                                                <option value="Male" {{ ($user->gender == 'Male') ? 'selected' : '' }}>
                                                    Male
                                                </option>
                                                <option value="Female" {{ ($user->gender == 'Female') ? 'selected' : '' }}>
                                                    Female
                                                </option>
                                            </select>

                                            @if ($errors->has('gender'))
                                                <span class="help-block">
                                                    {{ $errors->first('gender') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="form-group{{ $errors->has('birthday') ? ' has-error' : '' }}">
                                        <label class="col-md-3 control-label">Birthday</label>

                                        <div class="col-md-9">
                                            <input type="text" class="form-control date-picker" name="birthday"
                                                   value="{{ (old('birthday')) ? old('birthday') : $user->birthday }}">

                                            @if ($errors->has('birthday'))
                                                <span class="help-block">
                                                    {{ $errors->first('birthday') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('country_id') ? ' has-error' : '' }}">
                                        <label class="col-md-3 control-label">Country</label>

                                        <div class="col-md-9">
                                            <select class="form-control col-md-12 col-xs-12" name="country_id">
                                                <option value="">Select Country</option>
                                                @foreach($countries as $row)
                                                    <option value="{{$row->id}}"
                                                            {{ (old('country_id') == $row->id || $user->country_id == $row->id) ? 'selected' : '' }}>
                                                        {{$row->name}}
                                                    </option>
                                                @endforeach
                                            </select>

                                            @if ($errors->has('country_id'))
                                                <span class="help-block">
                                                    {{ $errors->first('country_id') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                                        <label class="col-md-3 control-label">Address</label>

                                        <div class="col-md-9">
                                                <textarea class="form-control" name="address"
                                                          rows="2">{{ (old('address')) ? old('address') : $user->address }}</textarea>

                                            @if ($errors->has('address'))
                                                <span class="help-block">
                                                    {{ $errors->first('address') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('about') ? ' has-error' : '' }}">
                                        <label class="col-md-3 control-label">About Me</label>

                                        <div class="col-md-9">
                                                <textarea class="form-control" name="about"
                                                          rows="2">{{ (old('about')) ? old('about') : $user->about }}</textarea>

                                            @if ($errors->has('about'))
                                                <span class="help-block">
                                                    {{ $errors->first('about') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-9 col-md-offset-3">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-floppy-o"></i> Save Changes
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection