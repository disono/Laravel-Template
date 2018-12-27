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
                <h1 class="header">{{ $view_title }}</h1>

                @include('admin.page.menu')

                <div class="row mt-3">
                    <div class="col">
                        <form action="{{ route('admin.page.update') }}" method="post"
                              v-on:submit.prevent="onFormUpload">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{ $page->id }}">

                            <div class="row">
                                <div class="col-md-9 col-sm-12 mb-3">
                                    <label for="content">Content/Body <strong class="text-danger">*</strong></label>

                                    <textarea name="content" id="content" class="form-control tiny-editor-content" rows="24"
                                              placeholder="Content">{!! old('content', $page->content) !!}</textarea>

                                    @if ($errors->has('content'))
                                        <div class="invalid-feedback">{{ $errors->first('content') }}</div>
                                    @endif
                                </div>

                                <div class="col-md-3 col-sm-12 mb-3">
                                    <div class="row mb-3">
                                        <label for="name">Page Name/Title <strong class="text-danger">*</strong></label>

                                        <input id="name" type="text"
                                               class="form-control{{ hasInputError($errors, 'name') }}"
                                               name="name" value="{{ old('name', $page->name) }}"
                                               data-validate="required">

                                        @if ($errors->has('name'))
                                            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                        @endif
                                    </div>

                                    <div class="row mb-3">
                                        <label for="slug">Slug (Friendly URL Name) <strong
                                                    class="text-danger">*</strong></label>

                                        <input id="slug" type="text"
                                               class="form-control{{ hasInputError($errors, 'slug') }}"
                                               name="slug" value="{{ old('slug', $page->slug) }}"
                                               data-validate="required">

                                        @if ($errors->has('slug'))
                                            <div class="invalid-feedback">{{ $errors->first('slug') }}</div>
                                        @endif
                                    </div>

                                    <div class="row mb-3">
                                        <label for="page_category_id">Category <strong
                                                    class="text-danger">*</strong></label>

                                        <select class="custom-select" name="page_category_id"
                                                id="page_category_id" data-validate="required">
                                            <option value="">Select Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}"
                                                        {{ frmIsSelected('page_category_id', $category->id, $page->page_category_id) }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>

                                        @if ($errors->has('page_category_id'))
                                            <div class="invalid-feedback">{{ $errors->first('page_category_id') }}</div>
                                        @endif
                                    </div>

                                    <div class="row mb-3">
                                        <label for="template">Template (Custom Page Design)</label>

                                        <input id="template" type="text"
                                               class="form-control{{ hasInputError($errors, 'template') }}"
                                               name="template" value="{{ old('template', $page->template) }}">

                                        @if ($errors->has('template'))
                                            <div class="invalid-feedback">{{ $errors->first('template') }}</div>
                                        @endif
                                    </div>

                                    <div class="row mb-3">
                                        <label for="cover_photo">Cover Photo</label>

                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="cover_photo"
                                                   id="cover_photo">
                                            <label class="custom-file-label" for="cover_photo">Choose image</label>
                                        </div>

                                        @if ($errors->has('cover_photo'))
                                            <div class="invalid-feedback">{{ $errors->first('cover_photo') }}</div>
                                        @endif
                                    </div>

                                    <div class="row mb-3">
                                        <label for="post_at">Post At</label>

                                        <input id="post_at" type="text"
                                               class="form-control date-picker-current{{ hasInputError($errors, 'post_at') }}"
                                               name="post_at" value="{{ old('post_at', $page->post_at) }}">

                                        @if ($errors->has('post_at'))
                                            <div class="invalid-feedback">{{ $errors->first('post_at') }}</div>
                                        @endif
                                    </div>

                                    <div class="row mb-3">
                                        <label for="expired_at">Expired At</label>

                                        <input id="expired_at" type="text"
                                               class="form-control date-picker-current{{ hasInputError($errors, 'expired_at') }}"
                                               name="expired_at" value="{{ old('expired_at', $page->expired_at) }}">

                                        @if ($errors->has('expired_at'))
                                            <div class="invalid-feedback">{{ $errors->first('expired_at') }}</div>
                                        @endif
                                    </div>

                                    <div class="row mb-3">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="is_draft"
                                                   value="1" name="is_draft"
                                                    {{ frmIsChecked('is_draft', 1, $page->is_draft) }}>
                                            <label class="custom-control-label" for="is_draft">Save as draft</label>
                                        </div>

                                        @if ($errors->has('is_draft'))
                                            <div class="invalid-feedback">{{ $errors->first('is_draft') }}</div>
                                        @endif
                                    </div>

                                    <div class="row mb-3">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input"
                                                   id="is_email_to_subscriber"
                                                   value="1" name="is_email_to_subscriber"
                                                    {{ frmIsChecked('is_email_to_subscriber', 1, $page->is_email_to_subscriber) }}>
                                            <label class="custom-control-label" for="is_email_to_subscriber">Email to
                                                subscribers</label>
                                        </div>

                                        @if ($errors->has('is_email_to_subscriber'))
                                            <div class="invalid-feedback">{{ $errors->first('is_email_to_subscriber') }}</div>
                                        @endif
                                    </div>

                                    <div class="row mb-3">
                                        <button type="submit" class="btn btn-raised btn-primary">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script src="{{ devAssets('assets/js/lib/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ devAssets('assets/js/vendor/tinyMCE.js') }}"></script>
@endsection