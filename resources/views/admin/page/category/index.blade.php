{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

@extends('admin.layouts.master')

@section('content')
    <h3 class="mb-3 font-weight-bold">{{ $view_title }}</h3>

    <div class="container-fluid shadow-sm p-3 bg-white">
        <div class="row">
            <div class="col">
                @include('admin.page.menu')
            </div>
        </div>

        <div class="row mt-3">
            <div class="col">
                <form method="get" action="{{ route('admin.pageCategory.browse') }}" id="frmTableFilter">
                    <input type="submit" style="display: none;">

                    @include('vendor.app.toolbar', ['createRoute' => 'admin.pageCategory.create', 'toolbarHasDel' => true])

                    @if(count($parents))
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/admin/page-categories">All</a></li>
                                @foreach($parents as $parent)
                                    <li class="breadcrumb-item {{ $parent->is_active ? 'active' : '' }}"
                                            {{ $parent->is_active ? 'aria-current="page"' : '' }}>
                                        {!! !$parent->is_active ? '<a href="/admin/page-categories?parent_id=' . $parent->id . '">' : '' !!}
                                        {{ $parent->name }}
                                        {!! !$parent->is_active ? '</a>' : '' !!}
                                    </li>
                                @endforeach
                            </ol>
                        </nav>
                    @endif

                    <div class="table-responsive-sm">
                        <table class="table table-bordered">
                            <thead class="table-borderless bg-light">
                            <tr>
                                {!! thDelete() !!}

                                <th scope="col">#</th>
                                <th scope="col">
                                    <input type="text" class="form-control form-control-sm" name="search"
                                           placeholder="Name" value="{{ $request->get('search') }}">
                                </th>
                                <th>
                                    <select class="form-control form-control-sm select_picker"
                                            data-style="btn-blue-50"
                                            name="is_enabled"
                                            @change="onSelectChangeSubmitForm($event, '#frmTableFilter')">
                                        <option value="">Enabled & Disabled (All)</option>
                                        <option value="1" {{ frmIsSelected('is_enabled', 1) }}>Enabled</option>
                                        <option value="0" {{ frmIsSelected('is_enabled', 0) }}>Disabled</option>
                                    </select>
                                </th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($page_categories as $row)
                                <tr id="parent_tr_{{$row->id}}">
                                    {!! tdDelete($row->id) !!}

                                    <td>{{ $row->id }}</td>
                                    <td>
                                        {!! $row->name !!}
                                    </td>
                                    <td>
                                        <label class="custom-control material-switch">
                                            <span class="material-switch-control-description">&nbsp;</span>
                                            <input type="checkbox"
                                                   class="material-switch-control-input is-checkbox-change"
                                                   name="status_is_enabled"
                                                   {{ $row->is_enabled ? 'checked' : '' }}
                                                   data-is-redirect="no"
                                                   data-uri="{{ url('admin/page-category/update/is_enabled/' . ($row->is_enabled ? 0 : 1) . '/' . $row->id) }}">
                                            <span class="material-switch-control-indicator"></span>
                                            <span class="material-switch-control-description">&nbsp;</span>
                                        </label>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-light btn-sm dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-cog"></i>
                                            </button>

                                            <div class="dropdown-menu">
                                                <a class="dropdown-item"
                                                   href="{{ url('admin/page-category/edit/' . $row->id )}}">Edit</a>

                                                @if($row->count_sub)
                                                    <a class="dropdown-item"
                                                       href="{{ url('admin/page-categories?parent_id=' . $row->id )}}">
                                                        Sub Categories ({{ $row->count_sub }})
                                                    </a>
                                                @endif

                                                <div class="dropdown-divider"></div>

                                                <a class="dropdown-item"
                                                   href="{{ url('admin/page-category/destroy/' . $row->id) }}"
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
                </form>

                @if(!count($page_categories))
                    <h1 class="text-center"><i class="far fa-frown"></i> No Page Category Found.</h1>
                @endif
            </div>
        </div>
    </div>
@endsection