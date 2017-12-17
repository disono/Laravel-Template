{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template
 * License: Apache 2.0
--}}
@extends(current_theme() . 'layout.master')

@section('title', ' - Login')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 mr-auto ml-auto">
                <div class="jumbotron jumbotron-sm">
                    <h2 class="text-center">Log In</h2>

                    @if(app_settings('auth_social_facebook')->value == 'enabled')
                        <a href="{{url('auth/social/facebook')}}"
                           class="btn btn-block btn-large btn-facebook z-depth-0">
                            <i class="fa fa-facebook" aria-hidden="true"></i>
                            Login using Facebook
                        </a>
                    @endif

                    <form role="form" method="POST" class="mt-3" action="{{ url('login') }}"
                          v-on:submit.prevent="onFormPost">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="email" class="control-label">Email or Username</label>

                            <input id="email" type="text"
                                   class="form-control{{ $errors->has('email') ? ' is-invalid invalid' : '' }}"
                                   name="email"
                                   value="{{ old('email') }}" data-validate="required">

                            @if ($errors->has('email'))
                                <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="password" class="control-label">Password</label>

                            <input id="password" type="password"
                                   class="form-control{{ $errors->has('password') ? ' is-invalid invalid' : '' }}"
                                   name="password" data-validate="required">

                            @if ($errors->has('password'))
                                <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <input type="checkbox" name="remember" id="remember"/>
                            <label for="remember">Remember Me</label>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn blue btn-block btn-large z-depth-0">Login</button>
                        </div>
                    </form>

                    <div class="row text-center">
                        <div class="col-md-12">
                            <a href="{{ url('password/recover') }}">Forgot Your Password?</a>
                        </div>

                        <div class="col-md-12">
                            No account? <a href="{{url('register')}}">Register now.</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
