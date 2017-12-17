{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template
 * License: Apache 2.0
--}}
@extends('admin.layout.master')

@section('title', $title)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h3>Edit Event</h3>

                <form action="{{url('admin/event/update')}}" method="post" role="form"
                      enctype="multipart/form-data">
                    {{csrf_field()}}
                    <input type="hidden" value="{{$event->id}}" name="id">

                    <div class="row">
                        <div class="col-sm-12 col-md-9">
                            <div class="form-group">
                                <label for="content">Content*</label>
                                <textarea name="content" id="content"
                                          class="form-control{{ $errors->has('content') ? ' is-invalid' : '' }}"
                                          rows="22"
                                          placeholder="Description">{!! $event->content !!}</textarea>

                                @if ($errors->has('content'))
                                    <div class="invalid-feedback">{{ $errors->first('content') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-3">
                            <div class="form-group">
                                <label for="name">Name*</label>
                                <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                       name="name" id="name"
                                       value="{{$event->name}}"
                                       placeholder="Name">

                                @if ($errors->has('name'))
                                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="slug">Slug* (Slugs make the URL more user-friendly)</label>
                                <input type="text" class="form-control{{ $errors->has('slug') ? ' is-invalid' : '' }}"
                                       name="slug" id="slug"
                                       value="{{$event->slug}}" placeholder="Slug">

                                @if ($errors->has('slug'))
                                    <div class="invalid-feedback">{{ $errors->first('slug') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="image">Cover(Optional)</label>
                                <input type="file" class="form-control{{ $errors->has('image') ? ' is-invalid' : '' }}"
                                       name="image" id="image">

                                @if ($errors->has('image'))
                                    <div class="invalid-feedback">{{ $errors->first('image') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="template">Template(Optional)</label>
                                <input type="text"
                                       class="form-control{{ $errors->has('template') ? ' is-invalid' : '' }}"
                                       name="template" id="template"
                                       placeholder="Template filename" value="{{$event->template}}">

                                @if ($errors->has('template'))
                                    <div class="invalid-feedback">{{ $errors->first('template') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="start_date">Start Date(Optional)</label>
                                <input type="text"
                                       class="form-control date-picker-min{{ $errors->has('start_date') ? ' is-invalid' : '' }}"
                                       name="start_date"
                                       id="start_date"
                                       placeholder="Start Date"
                                       value="{{old('start_date', $event->formatted_start_date)}}">

                                @if ($errors->has('start_date'))
                                    <div class="invalid-feedback">{{ $errors->first('start_date') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="start_time">Start Time(Optional)</label>
                                <input type="text"
                                       class="form-control time-picker{{ $errors->has('start_time') ? ' is-invalid' : '' }}"
                                       name="start_time"
                                       id="start_time"
                                       placeholder="Start Time"
                                       value="{{old('start_time', $event->formatted_start_time)}}">

                                @if ($errors->has('start_time'))
                                    <div class="invalid-feedback">{{ $errors->first('start_time') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="end_date">End Date(Optional)</label>
                                <input type="text"
                                       class="form-control date-picker-min{{ $errors->has('end_date') ? ' is-invalid' : '' }}"
                                       name="end_date"
                                       id="end_date"
                                       placeholder="End Date"
                                       value="{{old('end_date', $event->formatted_end_date)}}">

                                @if ($errors->has('end_date'))
                                    <div class="invalid-feedback">{{ $errors->first('end_date') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="end_time">End Time(Optional)</label>
                                <input type="text"
                                       class="form-control time-picker{{ $errors->has('end_time') ? ' is-invalid' : '' }}"
                                       name="end_time" id="end_time"
                                       placeholder="End Time"
                                       value="{{old('end_time', $event->formatted_end_time)}}">

                                @if ($errors->has('end_time'))
                                    <div class="invalid-feedback">{{ $errors->first('end_time') }}</div>
                                @endif
                            </div>

                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input {{ $errors->has('draft') ? ' is-invalid' : '' }}"
                                           type="checkbox" value="1"
                                           name="draft" {{($event->draft) ? 'checked' : null}}>
                                    Save as draft
                                </label>

                                @if ($errors->has('draft'))
                                    <div class="invalid-feedback">{{ $errors->first('draft') }}</div>
                                @endif
                            </div>

                            <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save
                                Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@include('vendor.loaders', ['scripts' => [
    asset('assets/js/lib/tinymce/tinymce.min.js')
]])