{{--
 * @author      Archie, Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @lincense    https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

@extends('admin.layouts.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <h1 class="header">{{ $view_title }}</h1>

                @include('admin.page.menu')
                @include('admin.page_category.menu')
            </div>
        </div>

        <div class="row">
            <div class="col mt-3">
                <form method="get" action="{{ route('admin.pageCategory.index') }}">
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <input type="text" class="form-control" placeholder="Search"
                                   name="search" value="{{ request('search') }}">
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <button class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col">
                @if(count($page_categories))
                    <div class="row">
                        <div class="col-md-12 mt-3">
                            <table class="table table-borderless">
                                <thead class="thead-dark">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($page_categories as $row)
                                    <tr id="parent_tr_{{$row->id}}">
                                        <td>{{ $row->id }}</td>

                                        <td>
                                            {!! $row->tab !!} {!! $row->name !!}
                                        </td>

                                        <td>
                                            <div class="btn-group btn-sm" role="group">
                                                <a class="btn btn-secondary"
                                                   href="{{url('admin/page-category/edit/' . $row->id)}}">Edit</a>
                                                <a class="btn btn-danger"
                                                   href="{{url('admin/page-category/destroy/' . $row->id)}}"
                                                   v-on:click.prevent="onDeleteResource($event, '#parent_tr_{{$row->id}}')">Delete</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <h1 class="text-center"><i class="far fa-frown"></i> No Page Category Created.</h1>
                @endif
            </div>
        </div>
    </div>
@endsection