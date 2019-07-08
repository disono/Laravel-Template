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
                <div class="col-md-12">
                    <h1 class="text-center mb-5">{{ $view_title }}</h1>

                    @foreach($pages->chunk(2) as $chunk)
                        <div class="row">
                            @foreach ($chunk as $page)
                                <div class="col-md-6">
                                    <div class="card mb-5 rounded-lg shadow bg-white border-0">
                                        <img src="{{ $page->cover_photo->primary }}" class="card-img-top" alt="{{ $page->name }}">

                                        <div class="card-body">
                                            <h2 class="card-title font-weight-bold">
                                                <a href="{{ $page->url }}" class="text-dark">{{ $page->name }}</a>
                                            </h2>
                                            <article class="card-text mb-3 text-muted">
                                                {{ str_limit(strip_tags($page->content), 42) }}
                                            </article>

                                            <div class="row">
                                                <div class="col">
                                                    <a href="{{ $page->url }}" class="btn btn-outline-primary">Read
                                                        more...</a>
                                                </div>
                                                <div class="col">
                                                    <h5 class="card-subtitle text-muted float-right">
                                                        <i class="fas fa-calendar-alt text-primary"></i> {{ $page->formatted_created_at }}
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                    {{ $pages->appends($request->all())->render() }}
                </div>
            @else
                <div class="col-12 p-3 rounded shadow-sm bg-white">
                    <h3 class="text-center">No Pages Found.</h3>
                </div>
            @endif
        </div>
    </div>
@endsection