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
                <h3 class="page-header">Subscribers</h3>

                {{-- search options --}}
                <div class="app-container">
                    <form action="" method="get" role="form" class="form-inline">
                        <div class="form-group">
                            <input type="text" class="form-control" name="search" id="search"
                                   value="{{$request->get('search')}}" placeholder="Keyword">
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                </div>

                <div class="app-container">
                    @if(count($subscriber))
                        <p>You have {{$subscriber_object->count()}} subscriber.</p>

                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($subscriber as $row)
                                <tr>
                                    <td>{{$row->id}}</td>
                                    <td>{{$row->full_name}}</td>
                                    <td>{{$row->email}}</td>
                                    <td>{{$row->created_at}}</td>
                                    <td><a href="{{url('admin/subscriber/destroy/' . $row->id)}}"
                                           class="delete-data">Delete</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        {{$subscriber->appends($request->all())->render()}}
                    @else
                        <h1 class="text-center">No subscriber.</h1>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection