{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

@extends(currentTheme() . 'layouts.master')

@section('content')
    <div class="container">
        <div class="row">
            @if(count($pages))
                @foreach($pages as $page)
                    <div class="col">
                        <div class="card mb-3 rounded shadow-sm bg-white border-0">
                            <div class="card-body">
                                <h5 class="card-title"><a href="{{ $page->url }}">{{ $page->name }}</a></h5>
                                <small class="card-subtitle mb-2 text-muted">{{ $page->formatted_created_at }}</small>
                                <p class="card-text">{{ $page->small_content }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{ $pages->appends($request->all())->render() }}
            @else
                <div class="col-12 p-3 rounded shadow-sm bg-white">
                    <h3 class="text-center">No Pages Found.</h3>
                </div>
            @endif
        </div>
    </div>
@endsection