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
                <div class="jumbotron jumbotron-sm shadow-sm">
                    <h2>Forgot Password</h2>

                    {{-- login form --}}
                    <form role="form" method="POST" class="mt-3" action="{{ route('auth.password.process.forgot') }}"
                          v-on:submit.prevent="onFormPost">
                        {{ csrf_field() }}

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
                            <button type="submit" class="btn btn-block btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection