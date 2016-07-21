{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: www.webmons.com
 * License: Apache 2.0
--}}
@extends(current_theme() . 'layout.master')

@section('title', $title)

@section('content')
    <div class="container-fluid has-header">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <article>{!! $page->content !!}</article>
            </div>
        </div>
    </div>
@endsection