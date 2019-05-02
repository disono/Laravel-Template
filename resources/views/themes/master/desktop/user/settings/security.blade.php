{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

@extends(currentTheme() . 'layouts.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-lg-3">
                @include(currentTheme() . 'user.settings.menu')
            </div>

            <div class="col-sm-12 col-lg-9 p-3 shadow-sm bg-white">
                <form role="form" method="POST" action="{{ route('user.security.update') }}"
                      enctype="multipart/form-data" v-on:submit.prevent="onFormPost">
                    {!! csrf_field() !!}

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" readonly class="form-control form-control-plaintext" id="username"
                               value="{{ $user->username }}" data-disabled="yes"/>
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
                               name="current_password" value="{{ old('current_password') }}" data-validate="required">

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

                    <button type="submit" class="btn btn-primary pull-right">
                        Save Changes
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection