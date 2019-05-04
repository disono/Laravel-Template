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
            <div class="col-md-5 mr-auto ml-auto">
                <div class="p-3 shadow-sm rounded bg-white">
                    <h2 class="text-center">Join Today.</h2>
                    <p class="text-muted text-center">Fill in the form below to get instant access.</p>

                    @if(__settings('authSocialFacebook')->value == 'enabled')
                        <a href="{{ route('auth.facebook') }}" class="btn btn-block btn-facebook btn-lg mt-3">
                            <i class="fab fa-facebook-square fa-lg"></i>
                            Continue with Facebook
                        </a>

                        <p class="hr-line-word mt-3"><span>OR</span></p>
                    @endif

                    <form role="form" method="POST" class="mt-3" action="{{ route('auth.register') }}"
                          v-on:submit.prevent="onFormPost">
                        {{ csrf_field() }}

                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label for="first_name">First Name</label>
                                    <input id="first_name" type="text"
                                           class="form-control{{ $errors->has('first_name') ? ' is-invalid invalid' : '' }}"
                                           name="first_name"
                                           value="{{ old('first_name') }}" data-validate="required|max:100">

                                    @if ($errors->has('first_name'))
                                        <div class="invalid-feedback">{{ $errors->first('first_name') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label for="last_name">Last Name</label>
                                    <input id="last_name" type="text"
                                           class="form-control{{ $errors->has('last_name') ? ' is-invalid invalid' : '' }}"
                                           name="last_name"
                                           value="{{ old('last_name') }}" data-validate="required|max:100">

                                    @if ($errors->has('last_name'))
                                        <div class="invalid-feedback">{{ $errors->first('last_name') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" type="email"
                                   class="form-control{{ $errors->has('email') ? ' is-invalid invalid' : '' }}"
                                   name="email"
                                   value="{{ old('email') }}" data-validate="required|email">

                            @if ($errors->has('email'))
                                <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text"
                                   class="form-control{{ $errors->has('username') ? ' is-invalid invalid' : '' }}"
                                   id="username" name="username" placeholder="" value="{{ old('username') }}"
                                   data-validate="required|min:4|max:100">

                            @if ($errors->has('username'))
                                <div class="invalid-feedback">{{ $errors->first('username') }}</div>
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input id="password" type="password"
                                           class="form-control{{ $errors->has('password') ? ' is-invalid invalid' : '' }}"
                                           name="password" data-validate="required|min:4|max:100">

                                    @if ($errors->has('password'))
                                        <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label for="password-confirm">Confirm Password</label>
                                    <input id="password-confirm" type="password"
                                           class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid invalid' : '' }}"
                                           name="password_confirmation" data-validate="required|same:password">

                                    @if ($errors->has('password_confirmation'))
                                        <div class="invalid-feedback">{{ $errors->first('password_confirmation') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn blue btn-block btn-primary">Register</button>
                        </div>
                    </form>

                    <div class="row">
                        <div class="col-md-12 text-center">
                            Already registered? <a class="btn-link" href="{{ route('auth.login') }}">Login here.</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection