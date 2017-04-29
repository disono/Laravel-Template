{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
--}}
@extends(current_theme() . 'layout.master')

@section('title', ' - Login')

@section('content')
    <div class="container-fluid has-header">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="jumbotron jumbotron-sm material-shadow-3">
                    <div class="container">
                        <h2 class="text-center">Log In</h2>

                        @if(app_settings('auth_social_facebook')->value == 'enabled')
                            <p><a href="{{url('auth/social/facebook')}}"
                                  class="btn btn-facebook btn-block btn-lg"><i class="fa fa-facebook" aria-hidden="true"></i> Login
                                    using Facebook</a></p>
                        @endif

                        <form role="form" method="POST" action="{{ url('login') }}" class="ajax-form"
                              enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="control-label">Email</label>

                                <input id="email" type="text" class="form-control" name="email"
                                       value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">{{ $errors->first('email') }}</span>
                                @endif
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="control-label">Password</label>

                                <input id="password" type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">{{ $errors->first('password') }}</span>
                                @endif
                            </div>

                            <div class="form-group">
                                <div class="checkbox">
                                    <input type="checkbox" name="remember" id="remember">
                                    <label for="remember">
                                        Remember Me
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block btn-lg">Login</button>
                            </div>
                        </form>

                        <div class="row">
                            <div class="col-md-9 col-md-offset-3">
                                <a class="btn btn-link" href="{{ url('password/recover') }}">Forgot Your
                                    Password?</a>
                            </div>

                            <div class="col-md-9 col-md-offset-3">
                                No account? <a href="{{url('register')}}">Register now.</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
