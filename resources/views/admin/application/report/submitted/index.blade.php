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
                @include('admin.application.report.menu')
            </div>
        </div>

        <div class="row mt-3">
            <div class="col">
                <form action="{{ route('admin.pageReport.browse') }}" method="get" id="frmTableFilter">
                    <input type="submit" style="display: none;">

                    @include('vendor.app.toolbar')

                    <div class="table-responsive-sm">
                        <table class="table table-bordered">
                            <thead class="table-borderless bg-light">
                            <tr>
                                {!! thDelete() !!}

                                <th>#</th>
                                <th>
                                    <select class="form-control form-control-sm select_picker"
                                            data-style="btn-blue-50"
                                            name="page_report_reason_id"
                                            data-live-search="true"
                                            @change="onSelectChangeSubmitForm($event, '#frmTableFilter')">
                                        <option value="">Reason (All)</option>
                                        @foreach($reasons as $row)
                                            <option value="{{ $row->id }}" {{ frmIsSelected('page_report_reason_id', $row->id) }}>{{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                </th>
                                <th>
                                    <select class="form-control form-control-sm select_picker"
                                            data-style="btn-blue-50"
                                            name="user_id"
                                            id="select_picker_submitted_by"
                                            data-live-search="true"
                                            data-live-path="{{ route('admin.pageReport.searchSubmittedBy.browse') }}"
                                            data-live-value="user_id"
                                            data-live-text="submitted_by"
                                            @change="onSelectChangeSubmitForm($event, '#frmTableFilter')">
                                        <option value="">Submitted by (All)</option>
                                    </select>
                                </th>
                                <th>
                                    <select class="form-control form-control-sm select_picker"
                                            data-style="btn-blue-50"
                                            name="responded_by_id"
                                            id="select_picker_process_by"
                                            data-live-search="true"
                                            data-live-path="{{ route('admin.pageReport.searchProcessedBy.browse') }}"
                                            data-live-value="responded_by_id"
                                            data-live-text="process_by"
                                            @change="onSelectChangeSubmitForm($event, '#frmTableFilter')">
                                        <option value="">Processed by (All)</option>
                                    </select>
                                </th>
                                <th>
                                    <select class="form-control form-control-sm select_picker"
                                            data-style="btn-blue-50"
                                            name="status"
                                            @change="onSelectChangeSubmitForm($event, '#frmTableFilter')">
                                        <option value="">Status (All)</option>
                                        @foreach($statuses as $row)
                                            <option value="{{ $row }}" {{ frmIsSelected('status', $row) }}>{{ $row }}</option>
                                        @endforeach
                                    </select>
                                </th>
                                <th>
                                    <input type="text" class="form-control form-control-sm date-picker-no-future"
                                           name="created_at"
                                           data-form-submit="#frmTableFilter"
                                           placeholder="Date" value="{{ $request->get('created_at') }}">
                                </th>
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($reports as $row)
                                <tr id="parent_tr_{{ $row->id }}">
                                    {!! tdDelete($row->id) !!}

                                    <th>{{ $row->id }}</th>
                                    <td>{{ $row->page_report_reason_name }}</td>
                                    <td>{{ $row->submitted_by }}</td>
                                    <td>{{ ($row->process_by) }}</td>
                                    <td>{{ $row->status }}</td>
                                    <td>{{ $row->formatted_created_at }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-light btn-sm dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-cog"></i>
                                            </button>

                                            <div class="dropdown-menu">
                                                <a class="dropdown-item"
                                                   href="{{ url('admin/submitted-report/show/' . $row->id) }}">View</a>

                                                <a class="dropdown-item"
                                                   href="{{ url('admin/submitted-report/destroy/' . $row->id) }}"
                                                   id="parent_tr_del_{{ $row->id }}"
                                                   v-on:click.prevent="onDeleteResource($event, '#parent_tr_{{$row->id}}')">Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>

                @include('vendor.app.pagination', ['_lists' => $reports])
            </div>
        </div>
    </div>
@endsection