{{--
 * @author      Archie, Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @lincense    https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

@extends(currentTheme() . 'layouts.master')

@section('content')
    <div class="container">
        <div class="row">
            @foreach($pages as $page)
                <div class="col">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title"><a href="{{ $page->url }}">{{ $page->name }}</a></h5>
                            <h6 class="card-subtitle mb-2 text-muted">{{ $page->formatted_created_at }}</h6>
                            <p class="card-text">{{ $page->small_content }}</p>
                        </div>
                    </div>
                </div>
            @endforeach

            {{$pages->appends($request->all())->render()}}
        </div>
    </div>
@endsection