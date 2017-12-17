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
                    <h2>Please verify phone number {{me()->phone}}.</h2>

                    <form action="{{url('phone/verify')}}" class="form-inline" role="form" method="POST">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="token">Code</label>
                            <input id="token" type="token"
                                   class="form-control{{ $errors->has('token') ? ' is-invalid' : '' }}" name="token"
                                   value="{{ old('token') }}">

                            @if ($errors->has('token'))
                                <div class="invalid-feedback">{{ $errors->first('token') }}</div>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary">Verify Phone</button>

                        <a class="btn btn-default" href="{{url('phone/resend/verification')}}">Resend
                            Verification</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection