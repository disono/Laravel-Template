{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
--}}
@extends(current_theme() . 'layout.master')

@section('title', ' - Profile')

@section('content')
    <div class="container-fluid has-header">
        <div class="row">
            <div class="col-sm-12 col-md-4 col-md-offset-4 text-center">
                <div class="well">
                    <div class="row">
                        <div class="">
                            <img src="{{$profile->avatar}}" alt="{{$profile->full_name}}" class="img-circle">
                        </div>

                        <div class="">
                            <h3>{{$profile->full_name}}</h3>
                            <p>{{$profile->about}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
