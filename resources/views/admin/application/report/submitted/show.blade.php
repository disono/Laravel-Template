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
            <div class="col-md-6 col-sm-12">
                <div class="row mb-3 p-3">
                    <div class="col text-center p-0">
                        <img class="rounded-circle shadow-sm" style="width: 92px;"
                             src="{{ $report->submitted_by_profile_picture }}" alt="{{ $report->submitted_by }}">

                        <h4 class="font-weight-bold">{{ $report->submitted_by }}</h4>

                        <div class="dropdown mt-3 mb-3">
                            <button class="btn btn-primary dropdown-toggle btn-sm" type="button"
                                    id="dropdownMenuButton"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ $report->status }}
                            </button>

                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                @foreach($statuses as $status)
                                    <a class="dropdown-item"
                                       href="{{ url('admin/submitted-report/status/' . $report->id . '/' . $status) }}">
                                        {{ $status }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <p class="m-0"><u>Date:</u></p>
                <p class="m-0">{{ $row->formatted_created_at }}</p>

                <p class="m-0 mt-3"><u>URL:</u></p>
                <p class="m-0">
                    @if($report->url)
                        <a href="{{ $report->url }}" target="_blank">{{ $report->url }}</a>
                    @else
                        n/a
                    @endif
                </p>

                <p class="m-0 mt-3"><u>Description:</u></p>
                <p class="m-0">{{ ($report->description) ? $report->description : 'n/a' }}</p>

                <p class="m-0 mt-3"><u>Reviewed & Processed by:</u></p>
                <p class="m-0 mb-3">{{ $report->process_by != 'n/a' && $report->process_by ? $report->process_by : 'Pending' }}</p>

                <p class="m-0 mt-3"><u>Screenshots:</u></p>
                <div class="m-0 mb-3 p-3">
                    <div class="row">
                        @foreach($report->screenshots as $img)
                            <img src="{{ $img->path }}" alt="{{ $img->file_name }}" class="img-thumbnail col-md-6 col-sm-12">
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-sm-12">
                @if(!in_array($report->status, ['Closed', 'Denied']))
                    <form role="form" method="POST" action="{{ route('admin.pageReport.sendMessage.store') }}"
                          v-on:submit.prevent="onFormPost">
                        <input type="hidden" name="page_report_id" value="{{ $report->id }}">

                        <div class="form-group">
                            <textarea name="message" id="message" class="form-control"
                                      placeholder="Post your message..."></textarea>
                        </div>

                        <div class="form-group text-right">
                            <button class="btn btn-primary">Post Reply</button>
                        </div>
                    </form>
                @else
                    <h3 class="text-center mt-3">This report is already "{{ $report->status }}".</h3>
                @endif

                <h4 class="font-weight-bold mb-3">
                    Report Messages
                </h4>
                <hr>

                @if(count($messages))
                    @foreach($messages as $msg)
                        <div class="media mt-3">
                            <img class="mr-3 rounded-circle shadow-sm" style="width: 64px;"
                                 src="{{ $msg->profile_picture }}" alt="{{ $msg->full_name }}">

                            <div class="media-body bg-light text-secondary p-3 rounded">
                                <h5 class="m-0 mb-2 text-secondary">
                                    <strong>{{ $msg->full_name }}</strong>
                                </h5>

                                <p class="m-0">{{ $row->formatted_created_at }}</p>
                                <p class="m-0">{{ $msg->message }}</p>
                            </div>
                        </div>
                    @endforeach

                    {{ $messages->appends($request->all())->render() }}
                @else
                    <h3 class="text-center mt-3"><i class="far fa-frown"></i> No Posted Messages Found.</h3>
                @endif
            </div>
        </div>
    </div>
@endsection