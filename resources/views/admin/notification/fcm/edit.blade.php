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
                <div class="row">
                    <div class="col">
                        <h1 class="header">{{ $view_title }}</h1>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col">
                        <form action="{{ route('admin.fcmNotification.store') }}" method="post"
                              v-on:submit.prevent="onFormUpload">
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <label for="title">Title <strong class="text-danger">*</strong></label>

                                    <input id="title" type="text"
                                           class="form-control{{ hasInputError($errors, 'title') }}"
                                           name="title" value="{{ old('title', $notification->title) }}"
                                           data-validate="required">

                                    @if ($errors->has('title'))
                                        <div class="invalid-feedback">{{ $errors->first('title') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <label for="content">Content <strong class="text-danger">*</strong></label>

                                    <input id="content" type="text"
                                           class="form-control{{ hasInputError($errors, 'content') }}"
                                           name="content" value="{{ old('content', $notification->content) }}"
                                           data-validate="required">

                                    @if ($errors->has('content'))
                                        <div class="invalid-feedback">{{ $errors->first('content') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <label for="type">Type <strong class="text-danger">*</strong></label>

                                    <select name="type" id="type" class="form-control" v-model="frmFCM.type"
                                            @change="frmFCMTokenOnChangeType">
                                        <option value="">Select Type</option>
                                        <option value="topic" {{ ($notification->type == 'topic') ? 'selected' : '' }}>
                                            Topic
                                        </option>
                                        <option value="token" {{ ($notification->type == 'token') ? 'selected' : '' }}>
                                            Token
                                        </option>
                                    </select>

                                    @if ($errors->has('type'))
                                        <div class="invalid-feedback">{{ $errors->first('type') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="row" v-if="frmFCM.type == 'topic'">
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <label for="topic_name">Topic Name <strong class="text-danger">*</strong></label>

                                    <input id="topic_name" type="text"
                                           class="form-control{{ hasInputError($errors, 'topic_name') }}"
                                           name="topic_name" value="{{ old('topic_name', $notification->topic_name) }}"
                                           data-validate="required"
                                           v-model="frmFCM.topic_name">

                                    @if ($errors->has('topic_name'))
                                        <div class="invalid-feedback">{{ $errors->first('topic_name') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="row" v-if="frmFCM.type == 'token'">
                                <div class="col-md-4 col-sm-12 mb-3">
                                    <label for="token">Token <strong class="text-danger">*</strong></label>

                                    <input id="token" type="text"
                                           class="form-control{{ hasInputError($errors, 'token') }}"
                                           name="token" value="{{ old('token', $notification->token) }}"
                                           data-validate="required"
                                           v-model="frmFCM.token">

                                    @if ($errors->has('token'))
                                        <div class="invalid-feedback">{{ $errors->first('token') }}</div>
                                    @endif
                                </div>
                            </div>

                            <button type="submit" class="btn btn-raised btn-primary">Send</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection