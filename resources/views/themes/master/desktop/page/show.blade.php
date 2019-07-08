{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

@extends(currentTheme() . 'layouts.master')

@section('content')
    <div class="container">
        <div class="row shadow bg-white mb-5 m-0">
            <div class="col-12 p-0 m-0">
                <div class="img-fluid col-12 p-0"
                     style="background-image: url('{{ $page->cover_photo->primary }}'); background-position: center top; background-size: 100% auto; height: 16rem;"></div>

                <div class="p-3 m-3">
                    <h5 class="card-subtitle text-muted mb-3">
                        <i class="fas fa-calendar-alt text-primary"></i> {{ $page->formatted_created_at }}
                    </h5>

                    <article>
                        {!! $page->content !!}
                    </article>

                    <p>
                        @foreach(explode(',', $page->tags) as $tag)
                            <a href="{{ route('page.tag.browse', ['tag' => urlTitle($tag)]) }}" class="btn btn-light btn-sm">#{{ $tag }}</a>
                        @endforeach
                    </p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <h1 class="font-weight-bold mb-3">Related Articles & Pages</h1>

                @foreach($related_articles->chunk(3) as $chunk)
                    <div class="row">
                        @foreach ($chunk as $page)
                            <div class="col-md-4">
                                <div class="card mb-5 rounded-lg shadow bg-white border-0">
                                    <img src="{{ $page->cover_photo->primary }}" class="card-img-top"
                                         alt="{{ $page->name }}">

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
            </div>
        </div>
    </div>
@endsection