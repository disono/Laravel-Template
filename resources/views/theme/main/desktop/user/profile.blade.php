{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template
 * License: Apache 2.0
--}}
@extends(current_theme() . 'layout.master')

@section('title', ' - Profile')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 text-center">
                <img src="{{$profile->avatar}}" alt="{{$profile->full_name}}" class="img-circle">

                <h3>{{$profile->full_name}}</h3>
                <p>{{$profile->about}}</p>
            </div>
        </div>
    </div>
@endsection
