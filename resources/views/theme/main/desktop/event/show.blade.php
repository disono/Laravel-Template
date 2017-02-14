{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
--}}
@extends(current_theme() . 'layout.master')

@section('title', $title)

@section('content')
    <div class="container has-header">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <h1>{{$event->name}}</h1>
                <p>{{$event->formatted_start_date}} {{$event->formatted_start_time}} - {{$event->formatted_end_date}} {{$event->formatted_end_time}}</p>

                <article>{!! $event->content !!}</article>
            </div>
        </div>
    </div>
@endsection