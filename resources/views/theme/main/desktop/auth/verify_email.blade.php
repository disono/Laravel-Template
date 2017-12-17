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
                    <h2>Please verify your email {{me()->email}}.</h2>

                    <a href="{{url('email/resend/verification')}}"
                       class="btn btn-primary">Resend Verification</a>
                </div>
            </div>
        </div>
    </div>
@endsection