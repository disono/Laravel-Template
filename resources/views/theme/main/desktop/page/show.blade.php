{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template
 * License: Apache 2.0
--}}
@extends(current_theme() . 'layout.master')

@section('title', $title)

@section('content')
    <div class="container top-header">
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <h1>{{$page->name}}</h1>

                <article>{!! $page->content !!}</article>
            </div>
        </div>
    </div>
@endsection