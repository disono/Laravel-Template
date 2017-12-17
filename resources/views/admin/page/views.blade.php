{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template
 * License: Apache 2.0
--}}
@extends('admin.layout.master')

@section('title', $title)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h3>Pages Views</h3>

                {{-- search options --}}
                <form action="" method="get" role="form" class="mt-3">
                    <div class="form-row">
                        <div class="col">
                            <input type="text" class="form-control" name="search" id="search"
                                   value="{{$request->get('search')}}" placeholder="Keyword">
                        </div>

                        <div class="col">
                            <input type="text" class="form-control date-picker-limit" name="date_range_from"
                                   id="date_range_from"
                                   value="{{$request->get('date_range_from')}}" placeholder="From Date">
                        </div>

                        <div class="col">
                            <input type="text" class="form-control date-picker-limit" name="date_range_to"
                                   id="date_range_to"
                                   value="{{$request->get('date_range_to')}}" placeholder="To Date">
                        </div>

                        <div class="col">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </form>

                <div class="row mt-3">
                    <div class="col-12">
                        @if(count($page_views))
                            <p>You have {{$page_view_object->count()}} page views.</p>

                            <table class="table table-hover table-responsive">
                                <thead class="thead-dark">
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
                            <h4 class="text-center">No page views.</h4>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection