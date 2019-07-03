{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

@extends(currentTheme() . 'layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 mr-auto ml-auto">
                <div class="p-3 shadow-sm rounded bg-white">
                    <h2>Log In</h2>

                    {{-- Facebook login --}}
                    @if(__settings('authSocialFacebook')->value === 'enabled')
                        <a href="{{ route('auth.facebook') }}"
                           class="btn btn-block btn-facebook btn-lg mt-3 rounded-lg">
                            <i class="fab fa-facebook-square fa-lg"></i>&nbsp;Continue with Facebook
                        </a>

                        <p class="hr-line-word mt-3"><span>OR</span></p>
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

                            <div class="input-group">
                                <input type="password" class="form-control" name="password"
                                       id="password" data-validate="required">
                                <div class="input-group-append">
                                    <button class="btn btn-blue-50-append btn-show-password" data-pass-to="password"
                                            type="button"><i class="far fa-eye"></i></button>
                                </div>
                            </div>

                            @if ($errors->has('password'))
                                <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label class="custom-control material-checkbox">
                                <input type="checkbox" class="material-control-input" id="remember" name="remember">
                                <span class="material-control-indicator"></span>
                                <span class="material-control-description">Remember Me</span>
                            </label>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-block btn-primary rounded-lg">Login</button>
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