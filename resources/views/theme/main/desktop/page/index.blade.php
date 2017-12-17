{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template
 * License: Apache 2.0
--}}
@extends(current_theme() . 'layout.master')

@section('content')
    <div class="container top-header">
        {{-- category name for page if applicable --}}
        @if(isset($category))
            <h4>{{$category->name}}</h4>
        @endif

        {{-- list of pages/blogs etc --}}
        <div class="row">
            <div class="col-sm-12 col-md-9">
                @if(count($pages))
                    @foreach($pages as $row)
                        <div class="panel panel-default">
                            <div class="panel-heading"><a href="{{$row->url}}">{{$row->name}}</a></div>

                            <div class="panel-body">
                                {!! str_limit($row->content, 88) !!}

                                <p><a href="{{$row->url}}" class="pull-right">Read more...</a></p>
                            </div>
                        </div>
                    @endforeach

                    {{$pages->appends($request->all())->render()}}
                @else
                    <h4 class="text-center">No Pages.</h4>
                @endif
            </div>

            {{-- sidebar (latest/archive) --}}
            <div class="col-sm-12 col-md-3">
                @include(current_theme() . 'page.sidebar')
            </div>
        </div>
    </div>
@endsection