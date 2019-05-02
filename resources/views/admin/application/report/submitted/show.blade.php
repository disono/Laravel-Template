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

        <div class="row mt-3">
            <div class="col">
                <div class="media">
                    <img class="mr-3 rounded-circle shadow-sm" style="width: 64px;"
                         src="{{ $report->user->profile_picture }}" alt="{{ $report->user->full_name }}">

                    <div class="media-body">
                        <h3 class="m-0 mb-2">
                            <strong>{{ $report->user->full_name }}</strong>
                        </h3>

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

                        <p class="m-0"><strong>Date Submitted:</strong></p>
                        <p class="m-0">{{ $report->created_at }}</p>
                        <p class="m-0 mt-3"><strong>URL:</strong></p>
                        <p class="m-0">
                            @if($report->url)
                                <a href="{{ $report->url }}" target="_blank">{{ $report->url }}</a>
                            @else
                                n/a
                            @endif
                        </p>
                        <p class="m-0 mt-3"><strong>Description:</strong></p>
                        <p class="m-0">{{ $report->description }}</p>
                    </div>
                </div>

                <h4 class="mt-3"><i class="fas fa-envelope-open"></i> Posted Messages
                    (Reviewed/Processed by: {{ ($report->process_by) ? $report->process_by->full_name : 'Pending' }})
                </h4>
                <hr>

                @if(!in_array($report->status, ['Closed', 'Denied']))
                    <form role="form" method="POST" action="{{ route('admin.submitted.report.message.store') }}"
                          v-on:submit.prevent="onFormPost">
                        <input type="hidden" name="page_report_id" value="{{ $report->id }}">

                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea name="message" id="message" class="form-control"
                                      placeholder="Post your message..."></textarea>
                        </div>

                        <div class="form-group text-right">
                            <button class="btn btn-primary">Post</button>
                        </div>
                    </form>
                @else
                    <h3 class="text-center mt-3">This report is already "{{ $report->status }}".</h3>
                @endif

                <h4 class="mt-3">
                    <i class="fas fa-mail-bulk"></i> Activities
                </h4>
                <hr>

                @if(count($messages))
                    @foreach($messages as $msg)
                        <div class="media mt-3">
                            <img class="mr-3 rounded-circle shadow-sm" style="width: 64px;"
                                 src="{{ $msg->user->profile_picture }}" alt="{{ $msg->user->full_name }}">

                            <div class="media-body bg-light text-secondary p-3 rounded">
                                <h5 class="m-0 mb-2 text-secondary">
                                    <strong>{{ $msg->user->full_name }}</strong>
                                </h5>

                                <p class="m-0">{{ humanDate($msg->created_at) }}</p>
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