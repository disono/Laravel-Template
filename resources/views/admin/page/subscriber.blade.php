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
                <h3>Subscribers</h3>

                {{-- search options --}}
                <form action="" method="get" role="form" class="mt-3">
                    <div class="form-row">
                        <div class="col">
                            <input type="text" class="form-control" name="search" id="search"
                                   value="{{$request->get('search')}}" placeholder="Keyword">
                        </div>

                        <div class="col">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </form>

                <div class="row mt-3">
                    <div class="col-12">
                        @if(count($subscriber))
                            <p>You have {{$subscriber_object->count()}} subscriber.</p>

                            <table class="table table-hover">
                                <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                                </thead>

                                <tbody class="app-container">
                                @foreach($subscriber as $row)
                                    <tr id="parent_tr_{{$row->id}}">
                                        <td>{{$row->id}}</td>
                                        <td>{{$row->full_name}}</td>
                                        <td>{{$row->email}}</td>
                                        <td>{{$row->created_at}}</td>
                                        <td><a href="{{url('admin/subscriber/destroy/' . $row->id)}}"
                                               v-on:click.prevent="onDeleteResource($event, '#parent_tr_{{$row->id}}')">Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            {{$subscriber->appends($request->all())->render()}}
                        @else
                            <h4 class="text-center">No subscriber.</h4>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection