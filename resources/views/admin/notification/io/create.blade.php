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
                <div class="row">
                    <div class="col">
                        @include('admin.notification.menu')
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col">
                        <form action="{{ route('admin.socketIoNotification.store') }}" method="post"
                              v-on:submit.prevent="onFormUpload">
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <label for="title">Title <strong class="text-danger">*</strong></label>

                                    <input id="title" type="text"
                                           class="form-control{{ hasInputError($errors, 'title') }}"
                                           name="title" value="{{ old('title') }}" data-validate="required">

                                    @if ($errors->has('title'))
                                        <div class="invalid-feedback">{{ $errors->first('title') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <label for="content">Content <strong class="text-danger">*</strong></label>

                                    <textarea name="content" id="content" rows="3" data-validate="required"
                                              class="form-control{{ hasInputError($errors, 'content') }}">{{ old('content') }}</textarea>

                                    @if ($errors->has('content'))
                                        <div class="invalid-feedback">{{ $errors->first('content') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <label for="type">Type <strong class="text-danger">*</strong></label>

                                    <select name="type" id="type" class="form-control select_picker"
                                            data-style="btn-blue-50"
                                            v-model="frmAdminIO.type"
                                            @change="frmAdminIOOnTypeChange">
                                        <option value="">Select Type</option>
                                        <option value="topic">Topic</option>
                                        <option value="token">Token</option>
                                    </select>

                                    @if ($errors->has('type'))
                                        <div class="invalid-feedback">{{ $errors->first('type') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="row" v-if="frmAdminIO.type === 'topic'">
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <label for="topic_name">Topic Name <strong class="text-danger">*</strong></label>

                                    <select name="topic_name" id="topic_name"
                                            data-style="btn-blue-50"
                                            class="form-control select_picker"
                                            data-validate="required">
                                        <option value="">Select Type</option>
                                        @foreach($topics as $topic)
                                            <option value="{{ $topic }}">{{ ucfirst($topic) }}</option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('topic_name'))
                                        <div class="invalid-feedback">{{ $errors->first('topic_name') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="row" v-if="frmAdminIO.type === 'token'">
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <label for="token">Token <strong class="text-danger">*</strong></label>

                                    <select name="token" id="token"
                                            data-style="btn-blue-50"
                                            class="form-control select_picker"
                                            data-live-search="true"
                                            data-live-path="/admin/socket-io-notification/tokens"
                                            data-live-value="token"
                                            data-live-text="full_name"
                                            data-validate="required">
                                        <option value="">Select Token</option>
                                    </select>

                                    @if ($errors->has('token'))
                                        <div class="invalid-feedback">{{ $errors->first('token') }}</div>
                                    @endif
                                </div>
                            </div>

                            <hr>
                            <button type="submit" class="btn btn-raised btn-primary">Send</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection