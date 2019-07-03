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
                <form action="{{ route('admin.page.browse') }}" method="get" id="frmTableFilter">
                    <input type="submit" style="display: none;">

                    @include('vendor.app.toolbar', ['createRoute' => 'admin.page.create', 'toolbarHasDel' => true])

                    <div class="table-responsive-sm">
                        <table class="table table-bordered">
                            <thead class="table-borderless bg-light">
                            <tr>
                                {!! thDelete() !!}

                                <th>#</th>
                                <th><input type="text" class="form-control form-control-sm" name="name"
                                           placeholder="Name" value="{{ $request->get('name') }}"></th>
                                <th><input type="text" class="form-control form-control-sm" name="slug"
                                           placeholder="Slug" value="{{ $request->get('slug') }}"></th>
                                <th>
                                    <select class="form-control form-control-sm select_picker"
                                            data-style="btn-blue-50"
                                            name="page_category_id"
                                            @change="onSelectChangeSubmitForm($event, '#frmTableFilter')">
                                        <option value="">Category (All)</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}"
                                                    {{ frmIsSelected('page_category_id', $category->id) }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </th>
                                <th>
                                    <select class="form-control form-control-sm select_picker"
                                            data-style="btn-blue-50"
                                            name="is_draft"
                                            @change="onSelectChangeSubmitForm($event, '#frmTableFilter')">
                                        <option value="">Draft (All)</option>
                                        <option value="1" {{ frmIsSelected('is_draft', 1) }}>Yes</option>
                                        <option value="0" {{ frmIsSelected('is_draft', 0) }}>No</option>
                                    </select>
                                </th>
                                <th>
                                    <select class="form-control form-control-sm select_picker"
                                            data-style="btn-blue-50"
                                            name="is_email_to_subscriber"
                                            @change="onSelectChangeSubmitForm($event, '#frmTableFilter')">
                                        <option value="">Email to subscriber (All)</option>
                                        <option value="1" {{ frmIsSelected('is_email_to_subscriber', 1) }}>Yes</option>
                                        <option value="0" {{ frmIsSelected('is_email_to_subscriber', 0) }}>No</option>
                                    </select>
                                </th>
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($pages as $row)
                                <tr id="parent_tr_{{ $row->id }}">
                                    {!! tdDelete($row->id) !!}

                                    <td>{{ $row->id }}</td>
                                    <td>{{ $row->name }}</td>
                                    <td>{{ $row->slug }}</td>
                                    <td>
                                        @php $_c = []; @endphp

                                        @foreach($row->categories as $category)
                                            @php $_c[] = $category->name; @endphp
                                        @endforeach

                                        @php echo implode(', ', $_c); @endphp
                                    </td>
                                    <td>
                                        <label class="custom-control material-switch">
                                            <span class="material-switch-control-description">&nbsp;</span>
                                            <input type="checkbox"
                                                   class="material-switch-control-input is-checkbox-change"
                                                   name="status_is_draft"
                                                   {{ $row->is_draft ? 'checked' : '' }}
                                                   data-is-redirect="no"
                                                   data-uri="{{ url('admin/page/update/is_draft/' . ($row->is_draft ? 0 : 1) . '/' . $row->id) }}">
                                            <span class="material-switch-control-indicator"></span>
                                            <span class="material-switch-control-description">&nbsp;</span>
                                        </label>
                                    </td>
                                    <td>
                                        <label class="custom-control material-switch">
                                            <span class="material-switch-control-description">&nbsp;</span>
                                            <input type="checkbox"
                                                   class="material-switch-control-input is-checkbox-change"
                                                   name="status_is_email_to_subscriber"
                                                   {{ $row->is_email_to_subscriber ? 'checked' : '' }}
                                                   data-is-redirect="no"
                                                   data-uri="{{ url('admin/page/update/is_email_to_subscriber/' . ($row->is_email_to_subscriber ? 0 : 1) . '/' . $row->id) }}">
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
                                                   href="{{ url('admin/page/edit/' . $row->id) }}">Edit</a>

                                                <a class="dropdown-item"
                                                   href="{{ route('admin.pageView.browse', ['page_id' => $row->id]) }}">Views</a>

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
                </form>

                @include('vendor.app.pagination', ['_lists' => $pages])
            </div>
        </div>
    </div>
@endsection