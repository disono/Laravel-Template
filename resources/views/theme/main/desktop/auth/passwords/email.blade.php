{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template
 * License: Apache 2.0
--}}
@extends(current_theme() . 'layout.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-6 offset-md-3">
                <div class="jumbotron">
                    <h2>Forgot Password</h2>

                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    <form class="form-horizontal" role="form" method="POST"
                          action="{{ url('password/recover') }}" v-on:submit.prevent="onFormPost">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="email" class="control-label">E-Mail Address</label>

                            <input id="email" type="email"
                                   class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                   name="email" value="{{ old('email') }}" data-validate="required|email">

                            @if ($errors->has('email'))
                                <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                            @endif
                        </div>

                        <button type="submit" class="btn blue z-depth-0">
                            Send Password Reset Link
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
