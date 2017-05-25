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
                <h3>Page Categories
                    <a href="{{url('admin/page-category/create')}}" class="btn btn-primary pull-right">Create New Page Category</a>
                </h3>

                <div class="app-container">
                    @if(count($page_categories))
                        <div class="row">
                            <div class="col-md-12">
                                @foreach(\App\Models\PageCategory::nestedToTabs(['include_tab' => false, 'strong' => true]) as $row)
                                    <p style="">
                                        {!! $row->tab !!}{!! '(' . $row->id . ') ' . $row->name !!} <a
                                                href="{{url('admin/page-category/edit/' . $row->id)}}">Edit</a>
                                        |
                                        <a href="{{url('admin/page-category/destroy/' . $row->id)}}"
                                           class="delete-data">Delete</a>
                                    </p>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <h1 class="text-center">No Page Category.</h1>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection