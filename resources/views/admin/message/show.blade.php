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
                <table class="table table-hover">
                    <thead class="thead-dark">
                    <tr>
                        <th>Reading Message</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr>
                        <td>From</td>
                        <td>{{$message->from_full_name}}</td>
                    </tr>

                    <tr>
                        <td>To</td>
                        <td>{{$message->to_full_name}}</td>
                    </tr>

                    <tr>
                        <td>Message</td>
                        <td>{{$message->message}}</td>
                    </tr>

                    <tr>
                        <td>File</td>
                        <td>
                            @if($message->file)
                                <a href="{{$message->file}}">{{$message->file}}</a>
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <td>Created At</td>
                        <td>{{$message->formatted_created_at}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection