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
                <h3 class="page-header">Products
                    <a href="{{url('admin/product/create')}}" class="btn btn-primary pull-right">Create Product</a>
                </h3>

                {{-- search options --}}
                <div class="app-container">
                    <form action="" method="get" role="form" class="form-inline">
                        <div class="form-group">
                            <input type="text" class="form-control" name="search" id="search"
                                   value="{{$request->get('search')}}" placeholder="Keyword">
                        </div>

                        <div class="form-group">
                            <label for="low_in_qty">Low in quantity</label>
                            <input type="checkbox" class="form-control" name="low_in_qty" id="low_in_qty"
                                   value="1" {{is_checked($request->get('low_in_qty'), 1)}}>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                </div>

                <div class="app-container">
                    @if(count($products))
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th></th>
                                <th>Name</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($products as $row)
                                <tr>
                                    <td>{{$row->id}}</td>
                                    <td><img src="{{$row->cover}}" alt="" width="64px" height="64px"></td>
                                    <td>{{$row->name}}</td>
                                    <td>{{$row->qty}}</td>
                                    <td>{{$row->srp_format}}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-xs dropdown-toggle"
                                                    data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                Action <span class="caret"></span>
                                            </button>

                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a href="{{url('admin/product/edit/' . $row->id)}}">Edit</a>
                                                </li>
                                                <li><a href="{{url('admin/product/destroy/' . $row->id)}}"
                                                       class="delete-data">Delete</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        {{$products->appends($request->all())->render()}}
                    @else
                        <h1 class="text-center">No product.</h1>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection