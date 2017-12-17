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
                        <h3>Security</h3>

                        <form role="form" method="POST" action="{{ url('user/update/security') }}"
                              enctype="multipart/form-data">
                            {!! csrf_field() !!}

                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" readonly class="form-control form-control-plaintext" id="username"
                                       value="{{$user->username}}"/>
                            </div>

                            <div class="form-group">
                                <label id="email">Email</label>
                                <input type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                       name="email"
                                       value="{{ old('email', $user->email) }}" id="email">

                                @if ($errors->has('email'))
                                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="current_password">Current Password</label>
                                <input type="password"
                                       class="form-control{{ $errors->has('current_password') ? ' is-invalid' : '' }}"
                                       name="current_password" value="{{ old('current_password') }}">

                                @if ($errors->has('current_password'))
                                    <div class="invalid-feedback">{{ $errors->first('current_password') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="password">New Password</label>
                                <input type="password"
                                       class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                       name="password"
                                       value="{{ old('password') }}">

                                @if ($errors->has('password'))
                                    <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation">Confirm New Password</label>
                                <input type="password"
                                       class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}"
                                       name="password_confirmation" value="{{ old('password_confirmation') }}">

                                @if ($errors->has('password_confirmation'))
                                    <div class="invalid-feedback">{{ $errors->first('password_confirmation') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-floppy-o"></i> Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection