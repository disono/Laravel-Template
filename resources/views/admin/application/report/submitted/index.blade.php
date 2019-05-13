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
                @include('admin.application.report.menu')
            </div>
        </div>

        <div class="row">
            <div class="col mt-3">
                <form method="get" action="{{ route('admin.role.list') }}">
                    <div class="row">
                        <div class="col-md-3 col-sm-12 mb-3 mb-sm-0">
                            <input type="text" class="form-control" placeholder="Search"
                                   name="search" value="{{ request('search') }}">
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
                @if(count($reports))
                    <div class="table-responsive-sm">
                        <table class="table table-bordered">
                            <thead class="table-borderless">
                            <tr>
                                <th>#</th>
                                <th>Reason</th>
                                <th>Submitted by</th>
                                <th>Processed by</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($reports as $row)
                                <tr id="parent_tr_{{$row->id}}">
                                    <th>{{ $row->id }}</th>
                                    <td>{{ $row->page_report_reason_name }}</td>
                                    <td>{{ $row->submitted_by }}</td>
                                    <td>{{ ($row->process_by) ? $row->process_by->full_name : 'n/a' }}</td>
                                    <td>{{ $row->status }}</td>
                                    <td>{{ humanDate($row->created_at) }}</td>
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
                                                   v-on:click.prevent="onDeleteResource($event, '#parent_tr_{{$row->id}}')">Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $reports->appends($request->all())->render() }}
                @else
                    <h3 class="text-center"><i class="far fa-frown"></i> No Report Submitted Found.</h3>
                @endif
            </div>
        </div>
    </div>
@endsection