{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: www.webmons.com
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
                            <h2>Please verify your email {{me()->email}}.</h2>
                            <a href="{{url('email/resend/verification')}}">Resend Verification</a>
                    	</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection