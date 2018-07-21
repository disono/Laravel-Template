{{--
 * @author      Archie, Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @lincense    https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

@extends('admin.layouts.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <h1 class="header">{{ $view_title }}</h1>

                @include('admin.page.menu')
            </div>
        </div>

        <div class="row">
            <div class="col mt-3">
                <form method="get" action="{{ route('admin.pageView.index') }}">
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <input type="text" class="form-control" placeholder="Search"
                                   name="search" value="{{ request('search') }}">
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <button class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <table class="table mt-3">
                    <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Page</th>
                        <th>Referrer</th>
                        <th>URL</th>
                        <th>IP</th>
                        <th>OS/Platform</th>
                        <th>Browser</th>
                        <th>Date</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($page_views as $row)
                        <tr id="parent_tr_{{$row->id}}">
                            <td>{{ $row->id }}</td>
                            <td>{{ $row->page_name }}</td>
                            <td>{{ $row->http_referrer }}</td>
                            <td>{{ $row->current_url }}</td>
                            <td>{{ $row->ip_address }}</td>
                            <td>{{ $row->platform }}</td>
                            <td>{{ $row->browser }}</td>
                            <th>{{ humanDate($row->created_at) }}</th>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                @if(!count($page_views))
                    <h3 class="text-center"><i class="far fa-frown"></i> No Page Views Created.</h3>
                @endif

                {{$page_views->appends($request->all())->render()}}
            </div>
        </div>
    </div>
@endsection