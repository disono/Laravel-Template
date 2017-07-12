{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
--}}
@extends('admin.layout.master')

@section('title', $title)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <h3>Pages Views</h3>

                {{-- search options --}}
                <div class="app-container">
                    <form action="" method="get" role="form" class="form-inline">
                        <div class="row row-con">
                            <div class="col-sm-12 col-md-3 form-group">
                                <input type="text" class="form-control" name="search" id="search"
                                       value="{{$request->get('search')}}" placeholder="Keyword">
                            </div>

                            <div class="col-sm-12 col-md-3 form-group">
                                <input type="text" class="form-control date-picker-none" name="date_range_from"
                                       id="date_range_from"
                                       value="{{$request->get('date_range_from')}}" placeholder="From Date">
                            </div>

                            <div class="col-sm-12 col-md-3 form-group">
                                <input type="text" class="form-control date-picker-none" name="date_range_to"
                                       id="date_range_to"
                                       value="{{$request->get('date_range_to')}}" placeholder="To Date">
                            </div>

                            <div class="col-sm-12 col-md-3 form-group">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </div>
                    </form>
                </div>

                @if(count($page_views))
                    <p>You have {{$page_view_object->count()}} page views.</p>

                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Auth ID</th>
                            <th>Referrer</th>
                            <th>URL</th>
                            <th>IP</th>
                            <th>Browser</th>

                            <th>Type</th>
                            <th>Source</th>

                            <th>Date</th>
                        </tr>
                        </thead>

                        <tbody class="app-container">
                        @foreach($page_views as $row)
                            <tr>
                                <td>{{$row->user_id}}</td>
                                <td>{{$row->http_referrer}}</td>
                                <td>{{$row->current_url}}</td>
                                <td>{{$row->ip_address}}</td>
                                <td>{{$row->browser}}</td>

                                <td>{{$row->type}}</td>
                                <td>{{$row->source_id}}</td>

                                <td>{{$row->created_at}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    {{$page_views->appends($request->all())->render()}}
                @else
                    <h1 class="text-center">No page views.</h1>
                @endif
            </div>
        </div>
    </div>
@endsection