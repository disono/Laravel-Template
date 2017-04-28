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
                <h3>Messages</h3>

                <div class="app-container">
                    @if(count($message))
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Message</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($message as $row)
                                <tr>
                                    <td>{{$row->id}}</td>
                                    <td>{{$row->from_full_name}}</td>
                                    <td>{{$row->to_full_name}}</td>
                                    <td>{{$row->limit_message}}</td>
                                    <td><a href="{{url('admin/message/show/' . $row->id)}}">View</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        {{$message->appends($request->all())->render()}}
                    @else
                        <h1 class="text-center">No messages.</h1>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection