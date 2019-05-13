{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

@extends('admin.layouts.master')

@section('content')
    <div class="container-fluid shadow-sm p-3 bg-white">
        <div class="row">
            <div class="col">
                <h3>{{ $view_title }}</h3>
                <hr>
                @include('admin.page.menu')
            </div>
        </div>

        <div class="row mt-3">
            <div class="col">
                <form method="get" action="{{ route('admin.page.list') }}">
                    <div class="row">
                        <div class="col-md-3 col-sm-12 mb-3 mb-sm-0">
                            <input type="text" class="form-control" placeholder="Search"
                                   name="search" value="{{ request('search') }}">
                        </div>

                        <div class="col-md-3 col-sm-12 mb-3 mb-sm-0">
                            <select class="form-control select_picker" name="page_category_id">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ frmIsSelected('page_category_id', $category->id) }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mt-sm-3">
                        <div class="col">
                            <button class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col">
                @if(count($pages))
                    <div class="table-responsive-sm">
                        <table class="table table-bordered">
                            <thead class="table-borderless">
                            <tr>
                                {!! thDelete() !!}

                                <th>Name</th>
                                <th>Category</th>
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($pages as $row)
                                <tr id="parent_tr_{{ $row->id }}">
                                    {!! tdDelete($row->id) !!}

                                    <td>{{ $row->name }}</td>
                                    <td>{{ $row->page_category_slug }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-light btn-sm dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-cog"></i>
                                            </button>

                                            <div class="dropdown-menu">
                                                <a class="dropdown-item"
                                                   href="{{ url('admin/page/edit/' . $row->id) }}">Edit</a>

                                                <a class="dropdown-item"
                                                   href="{{ route('admin.page.view', ['page_id' => $row->id]) }}">Views</a>

                                                <div class="dropdown-divider"></div>

                                                <a class="dropdown-item"
                                                   href="{{ url('admin/page/destroy/' . $row->id) }}"
                                                   id="parent_tr_del_{{ $row->id }}"
                                                   v-on:click.prevent="onDeleteResource($event, '#parent_tr_{{ $row->id }}')">Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $pages->appends($request->all())->render() }}
                @else
                    <h3 class="text-center"><i class="far fa-frown"></i> No Pages Found.</h3>
                @endif
            </div>
        </div>
    </div>
@endsection