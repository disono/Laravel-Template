{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

@extends('admin.layouts.master')

@section('content')
    <div class="container-fluid shadow-sm p-3 bg-white">
        <div class="row">
            <div class="col">
                <h3>{{ $view_title }}</h3>
                <hr>
                @include('admin.page.menu')
            </div>
        </div>

        <div class="row mt-3">
            <div class="col">
                <form method="get" action="{{ route('admin.page.view') }}">
                    <div class="row">
                        <div class="col-md-3 col-sm-12 mb-3 mb-sm-0">
                            <input type="text" class="form-control" placeholder="Search"
                                   name="search" value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="row mt-sm-3">
                        <div class="col">
                            <button class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col">
                @if(count($page_views))
                    <div class="table-responsive-sm">
                        <table class="table table-bordered">
                            <thead class="table-borderless">
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
                    </div>

                    {{ $page_views->appends($request->all())->render() }}
                @else
                    <h3 class="text-center"><i class="far fa-frown"></i> No Page Views Found.</h3>
                @endif
            </div>
        </div>
    </div>
@endsection