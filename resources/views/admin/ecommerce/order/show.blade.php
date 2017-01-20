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
                <h3 class="page-header">Ordered Items
                </h3>

                <div class="app-container">
                    @if(count($ordered_items))
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th></th>
                                <th>Product Name</th>
                                <th>Qty</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($ordered_items as $row)
                                <tr>
                                    <td>{{$row->id}}</td>
                                    <td><img src="{{$row->cover}}" alt="" width="64px" height="64px"></td>
                                    <td>{{$row->product_name}}</td>
                                    <td>{{$row->qty}}</td>
                                    <td>{{$row->formatted_total}}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-xs dropdown-toggle"
                                                    data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                {{$row->status}} <span class="caret"></span>
                                            </button>

                                            <ul class="dropdown-menu">
                                                @foreach(order_status() as $status)
                                                    <li>
                                                        <a href="{{url('admin/order/item/' . $row->id . '/' . $status)}}">{{$status}}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        {{$ordered_items->appends($request->all())->render()}}
                    @else
                        <h1 class="text-center">No orders.</h1>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection