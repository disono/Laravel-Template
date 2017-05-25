{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
--}}

{{-- Latest --}}
<div class="panel panel-default">
    <div class="panel-heading">Latest</div>

    <ul class="list-group">
        {{-- formatting for category for page page|blog --}}
        @foreach(\App\Models\Page::latest('page') as $page_latest)
            <li class="list-group-item"><a
                        href="{{$page_latest->url}}">{{$page_latest->name}}</a></li>
        @endforeach
    </ul>
</div>

{{-- Archives --}}
<div class="panel panel-default">
    <div class="panel-heading">Archives</div>

    <ul class="list-group">
        @foreach(\App\Models\Page::archive() as $archive_row)
            <li class="list-group-item">
                <strong>{{$archive_row['top']->year_name}}</strong></li>

            @foreach($archive_row['sub'] as $sub_page)
                <li class="list-group-item">&nbsp;&nbsp;&nbsp;<a
                            href="{{url('pages?search_month=' . $sub_page->month_name . '&search_year=' . $archive_row['top']->year_name)}}">{{$sub_page->month_name}}</a>
                </li>
            @endforeach
        @endforeach
    </ul>
</div>