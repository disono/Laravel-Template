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
                <h3>Page Categories
                    <a href="{{url('admin/page-category/create')}}" class="btn btn-primary pull-right">Create New Page
                        Category</a>
                </h3>

                <div class="row mt-3">
                    <div class="col-12">
                        @if(count($page_categories))
                            <div class="row">
                                <div class="col-md-12">
                                    @foreach(\App\Models\PageCategory::nestedToTabs(['include_tab' => false, 'strong' => true]) as $row)
                                        <p id="parent_tr_{{$row->id}}">
                                            {!! $row->tab !!}{!! '(' . $row->id . ') ' . $row->name !!} <a
                                                    href="{{url('admin/page-category/edit/' . $row->id)}}">Edit</a>
                                            |
                                            <a href="{{url('admin/page-category/destroy/' . $row->id)}}"
                                               v-on:click.prevent="onDeleteResource($event, '#parent_tr_{{$row->id}}')">Delete</a>
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
    </div>
@endsection