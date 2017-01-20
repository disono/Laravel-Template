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
            <h2>Product Categories <a href="{{url('admin/product/category/create')}}"
                                      class="btn btn-danger pull-right">Create Product
                    Category</a></h2>

            <div class="app-container">
                <form action="" method="get" role="form" class="form-inline">
                    <div class="form-group">
                        <input type="text" class="form-control" name="search" id="search"
                               value="{{$request->get('search')}}" placeholder="Keyword">
                    </div>

                    <button type="submit" class="btn btn-danger">Search</button>
                </form>
            </div>

            <div class="app-container">
                @if(count($product_categories))
                    <br><br>
                    <div class="row">
                        <div class="col-md-12">
                            @foreach(\App\Models\ECommerce\ProductCategory::nestedToTabs(['include_tab' => false, 'strong' => true]) as $row)
                                <p style="">
                                    {!! $row->tab !!}{!! '(' . $row->id . ') ' . $row->name !!} <a
                                            href="{{url('admin/product/category/edit/' . $row->id)}}">Edit</a> |
                                    <a href="{{url('admin/product?product_category_id=' . $row->id)}}">Products</a> |
                                    <a href="{{url('admin/product/category/destroy/' . $row->id)}}"
                                       class="delete-data">Delete</a>
                                </p>
                            @endforeach
                        </div>
                    </div>
                @else
                    <h1 class="text-center">No Product Category.</h1>
                @endif
            </div>
        </div>
    </div>
@endsection