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
                        <h3>Security</h3>

                        <form class="form-horizontal" role="form" method="POST"
                              action="{{ url('user/update/security') }}" enctype="multipart/form-data">
                            {!! csrf_field() !!}

                            <div class="form-group">
                                <label class="col-md-4 control-label">Username</label>

                                <div class="col-md-6">
                                    <p class="form-control-static">{{$user->username}}</p>
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">Email</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="email"
                                           value="{{ (old('email')) ? old('email') : $user->email }}">

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            {{ $errors->first('email') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('current_password') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">Current Password</label>

                                <div class="col-md-6">
                                    <input type="password" class="form-control" name="current_password"
                                           value="{{ old('current_password') }}">

                                    @if ($errors->has('current_password'))
                                        <span class="help-block">
                                            {{ $errors->first('current_password') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">New Password</label>

                                <div class="col-md-6">
                                    <input type="password" class="form-control" name="password"
                                           value="{{ old('password') }}">

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            {{ $errors->first('password') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">Confirm New Password</label>

                                <div class="col-md-6">
                                    <input type="password" class="form-control" name="password_confirmation"
                                           value="{{ old('password_confirmation') }}">

                                    @if ($errors->has('password_confirmation'))
                                        <span class="help-block">
                                            {{ $errors->first('password_confirmation') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
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