{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

@extends(currentTheme() . 'layouts.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-lg-3">
                @includeTheme('user.settings.menu')
            </div>

            <div class="col-sm-12 col-lg-9">
                <div class="p-3 shadow-sm rounded bg-white border-0">
                    <h3 class="text-muted">{{ $report->page_report_reason_name }}</h3>
                    <p>Ticket # {{ $report->id }} Opened {{ $report->formatted_created_at }} Status <span
                                class="badge badge-success">{{ $report->status }}</span></p>

                    @if(count($report->screenshots))
                        <p class="m-0 mt-3"><u>Screenshots:</u></p>
                        <div class="m-0 mb-3 p-3">
                            <div class="row">
                                @foreach($report->screenshots as $img)
                                    <img src="{{ $img->path }}" alt="{{ $img->file_name }}"
                                         class="img-thumbnail col-md-3 col-sm-12 border-0 m-0 p-0">
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(!in_array($report->status, ['Closed', 'Denied']))
                        <form role="form" method="POST" action="{{ route('module.pageReport.sendMessage.store') }}"
                              v-on:submit.prevent="onFormPost">
                            <input type="hidden" name="page_report_id" value="{{ $report->id }}">

                            <div class="form-group">
                                <textarea name="message" id="message" class="form-control"
                                          placeholder="Post your message..."
                                          data-validate="required"></textarea>
                            </div>

                            <div class="form-group text-right">
                                <button class="btn btn-primary">Post Reply</button>
                            </div>
                        </form>
                    @else
                        <div class="text-center mb-3">
                            <h3 class="mt-3">This report is already "{{ $report->status }}".</h3>
                            <a href="{{ route('module.pageReport.status.update', ['id' => $report->id]) }}"
                               class="btn btn-success">Request to Reopen</a>
                        </div>
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

                                    <p class="m-0">{{ $msg->formatted_created_at }}</p>
                                    <p class="m-0">{{ $msg->message }}</p>
                                </div>
                            </div>
                        @endforeach

                        <div class="w-100 mt-3">
                            @include('vendor.app.pagination', ['_lists' => $messages])
                        </div>
                    @else
                        <h3 class="text-center mt-3"><i class="far fa-frown"></i> No Posted Messages Found.</h3>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection