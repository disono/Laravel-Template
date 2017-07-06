{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
--}}
@extends(current_theme() . 'layout.master')

@section('title', ' - Register')

@section('content')
    <div class="container-fluid has-header">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="jumbotron jumbotron-sm material-shadow-3">
                    <div class="container">
                        <h2 class="text-center">Join Today.</h2>
                        <p class="text-muted text-center">Fill in the form below to get instant access.</p>

                        @if(app_settings('auth_social_facebook')->value == 'enabled')
                            <p><a href="{{url('auth/social/facebook')}}"
                                  class="btn btn-facebook btn-block btn-lg"><i class="fa fa-facebook"
                                                                               aria-hidden="true"></i>
                                    Register
                                    using Facebook</a></p>
                        @endif

                        <form class="ajax-form" role="form" method="POST" action="{{url('register')}}">
                            {{csrf_field()}}

                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group{{$errors->has('first_name') ? ' has-error' : '' }}">
                                        <label for="first_name" class="control-label">First Name</label>

                                        <input id="first_name" type="text" class="form-control" name="first_name"
                                               value="{{ old('first_name') }}">

                                        @if ($errors->has('first_name'))
                                            <span class="help-block">{{ $errors->first('first_name') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                                        <label for="last_name" class="control-label">Last Name</label>

                                        <input id="last_name" type="text" class="form-control" name="last_name"
                                               value="{{old('last_name')}}">

                                        @if ($errors->has('last_name'))
                                            <span class="help-block">{{ $errors->first('last_name') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="control-label">Email</label>

                                <input id="email" type="email" class="form-control" name="email"
                                       value="{{old('email')}}">

                                @if ($errors->has('email'))
                                    <span class="help-block">{{ $errors->first('email') }}</span>
                                @endif
                            </div>

                            <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                <label for="username" class="control-label">Username</label>

                                <input type="text" class="form-control" id="username" name="username"
                                       placeholder="" value="{{old('username')}}">

                                @if ($errors->has('username'))
                                    <span class="help-block">{{ $errors->first('username') }}</span>
                                @endif
                            </div>

                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                        <label for="password" class="control-label">Password</label>

                                        <input id="password" type="password" class="form-control" name="password">

                                        @if ($errors->has('password'))
                                            <span class="help-block">{{ $errors->first('password') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                        <label for="password-confirm" class="control-label">Confirm Password</label>

                                        <input id="password-confirm" type="password" class="form-control"
                                               name="password_confirmation">

                                        @if ($errors->has('password_confirmation'))
                                            <span class="help-block">{{ $errors->first('password_confirmation') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block btn-lg">Register</button>
                            </div>
                        </form>

                        <div class="row">
                            <div class="col-md-12 text-center">
                                Already registered? <a class="btn-link" href="{{url('login')}}">Login here.</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
