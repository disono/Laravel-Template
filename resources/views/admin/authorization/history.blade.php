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
                <h3 class="page-header">Authorization Histories</h3>

                <div class="app-container">
                    @if(count($authorization_histories))
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Date/Time</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($authorization_histories as $row)
                                <tr>
                                    <td>{{$row->full_name}}</td>
                                    <td>{{$row->type}}</td>
                                    <td>{{$row->created_at}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        {{$authorization_histories->appends($request->all())->render()}}
                    @else
                        <h1 class="text-center">No Authorization History.</h1>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection