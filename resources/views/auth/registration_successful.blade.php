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
                            <h2>Your registration is successful, Thank You for using our App.</h2>

                            <a href="{{url('/')}}">Back to Homepage</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection