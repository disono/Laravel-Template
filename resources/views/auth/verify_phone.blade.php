{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
--}}
@extends(current_theme() . 'layout.master')

@section('content')
    <div class="container-fluid has-header">
        <div class="row">
            <div class="container text-center">
                <div class="col-md-12">
                    <div class="jumbotron jumbotron-sm">
                        <div class="container">
                            <h2>Please verify phone number {{me()->phone}}.</h2>

                            <form action="{{url('phone/verify')}}" class="form-inline" role="form" method="POST">
                                {{ csrf_field() }}

                                <div class="form-group{{ $errors->has('token') ? ' has-error' : '' }}">
                                    <label for="token" class="col-md-3 control-label">Code</label>

                                    <div class="col-md-9">
                                        <input id="token" type="token" class="form-control" name="token"
                                               value="{{ old('token') }}">

                                        @if ($errors->has('token'))
                                            <span class="help-block">
                                                {{ $errors->first('token') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    Verify Phone
                                </button>

                                <a class="btn btn-default" href="{{url('phone/resend/verification')}}">Resend
                                    Verification</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection