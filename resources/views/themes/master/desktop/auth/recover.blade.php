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
                <div class="jumbotron jumbotron-sm">
                    <h2>Recover Password</h2>

                    {{-- login form --}}
                    <form role="form" method="POST" class="mt-3" action="{{ route('auth.password.process.recover') }}"
                          v-on:submit.prevent="onFormPost">
                        {{ csrf_field() }}
                        <input type="hidden" name="reset_token" value="{{ $reset_token }}">

                        <div class="form-group">
                            <label for="email">Email</label>

                            <input id="email" type="email"
                                   class="form-control{{ $errors->has('email') ? ' is-invalid invalid' : '' }}"
                                   name="email" value="{{ old('email') }}" data-validate="required">

                            @if ($errors->has('email'))
                                <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="password">New Password</label>

                            <input id="password" type="password"
                                   class="form-control{{ $errors->has('password') ? ' is-invalid invalid' : '' }}"
                                   name="password" value="{{ old('password') }}" data-validate="required">

                            @if ($errors->has('password'))
                                <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Confirm New Password</label>

                            <input id="password_confirmation" type="password"
                                   class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid invalid' : '' }}"
                                   name="password_confirmation" value="{{ old('password_confirmation') }}"
                                   data-validate="required">

                            @if ($errors->has('password_confirmation'))
                                <div class="invalid-feedback">{{ $errors->first('password_confirmation') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-block">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection