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
                    <h2>Your registration is successful, Thank You for using our App.</h2>

                    <a href="{{url('/')}}">Back to Homepage</a>
                </div>
            </div>
        </div>
    </div>
@endsection