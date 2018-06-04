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
            <div class="col">
                @if($page->has_cover)
                    <div class="row">
                        <img src="{{ $page->cover }}" alt="{{ $page->name }}" class="img-fluid col-12">
                    </div>
                @endif

                <h1>{{ $page->name }}</h1>
                <h6 class="card-subtitle mb-2 text-muted">{{ $page->formatted_created_at }}</h6>

                <article>
                    {!! $page->content !!}
                </article>
            </div>
        </div>
    </div>
@endsection