{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: www.webmons.com
 * License: Apache 2.0
--}}
@extends(current_theme() . 'layout.master')

@section('title', ' - Login')

@section('content')
    <div class="container-fluid has-header">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="jumbotron jumbotron-sm">
                	<div class="container">
                        <h2>Log in to {{app_header('title')}}</h2>
                        <hr>

                        <form class="form-horizontal" role="form" method="POST" action="{{ url('login') }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-3 control-label">E-Mail Address</label>

                                <div class="col-md-9">
                                    <input id="email" type="email" class="form-control" name="email"
                                           value="{{ old('email') }}">

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            {{ $errors->first('email') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-md-3 control-label">Password</label>

                                <div class="col-md-9">
                                    <input id="password" type="password" class="form-control" name="password">

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                        {{ $errors->first('password') }}
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-9 col-md-offset-3">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="remember"> Remember Me
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-9 col-md-offset-3">
                                    <button type="submit" class="btn btn-primary">
                                        Login
                                    </button>

                                    <a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your
                                        Password?</a>
                                </div>
                            </div>
                        </form>

                        <hr>
                        <div class="row">
                            <div class="col-md-9 col-md-offset-3">
                                No account? <a href="{{url('register')}}">Regsiter now.</a>
                            </div>
                        </div>
                	</div>
                </div>
            </div>
        </div>
    </div>
@endsection
