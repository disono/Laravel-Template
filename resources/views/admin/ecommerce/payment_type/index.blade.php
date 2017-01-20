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
                <h3 class="page-header">Payment Types
                    <small><a href="{{url('admin/ecommerce/payment-type/create')}}" class="btn btn-primary pull-right">Create
                            Payment Type</a></small>
                </h3>

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
                    @if(count($payment_type))
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($payment_type as $row)
                                <tr>
                                    <td>{{$row->id}}</td>
                                    <td>{{$row->name}}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-xs dropdown-toggle"
                                                    data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                Action <span class="caret"></span>
                                            </button>

                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a href="{{url('admin/ecommerce/payment-type/edit/' . $row->id)}}">Edit</a>
                                                </li>
                                                <li>
                                                    <a href="{{url('admin/ecommerce/payment-type/destroy/' . $row->id)}}"
                                                       class="delete-data">Delete</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        {{$payment_type->appends($request->all())->render()}}
                    @else
                        <h1 class="text-center">No product.</h1>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection