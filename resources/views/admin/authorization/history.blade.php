{{--
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
--}}
@extends('admin.layout.master')

@section('title', $title)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h3>Authorization Histories</h3>

                <div class="row mt-3">
                    <div class="col-12">
                        @if(count($authorization_histories))
                            <table class="table table-hover">
                                <thead class="thead-dark">
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
                                        <td>{{human_date($row->created_at)}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            {{$authorization_histories->appends($request->all())->render()}}
                        @else
                            <h4 class="text-center">No Authorization History.</h4>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection