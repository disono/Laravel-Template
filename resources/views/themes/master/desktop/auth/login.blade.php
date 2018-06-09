{{--
 * @author      Archie, Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @lincense    https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

@extends(currentTheme() . 'layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 mr-auto ml-auto">
                <div class="jumbotron jumbotron-sm">
                    <h2>Log In</h2>

                    {{-- Facebook login --}}
                    @if(__settings('authSocialFacebook')->value == 'enabled')
                        <a href="{{ route('auth.facebook') }}"
                           class="btn btn-block">
                            <i class="fab fa-facebook-square fa-lg"></i>
                            Login using Facebook
                        </a>
                    @endif

                    {{-- login form --}}
                    <form role="form" method="POST" class="mt-3" action="{{ route('auth.login') }}"
                          v-on:submit.prevent="onFormPost">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="username">Email or Username</label>

                            <input id="username" type="text"
                                   class="form-control{{ $errors->has('username') ? ' is-invalid invalid' : '' }}"
                                   name="username" value="{{ old('username') }}" data-validate="required">

                            @if ($errors->has('username'))
                                <div class="invalid-feedback">{{ $errors->first('username') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>

                            <input id="password" type="password"
                                   class="form-control{{ $errors->has('password') ? ' is-invalid invalid' : '' }}"
                                   name="password" data-validate="required">

                            @if ($errors->has('password'))
                                <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="remember" name="remember">
                                <label class="custom-control-label" for="remember">Remember Me</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-block">Login</button>
                        </div>
                    </form>

                    <div class="row text-center">
                        <div class="col-md-12">
                            <a href="{{ route('auth.password.forgot') }}">Forgot Your Password?</a>
                        </div>

                        <div class="col-md-12">
                            No account? <a href="{{ route('auth.register') }}">Register now.</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection