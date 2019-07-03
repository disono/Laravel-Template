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
            <div class="col-sm-12 col-md-4">
                <form action="{{ route('admin.pageCategory.update') }}" method="post" v-on:submit.prevent="onFormPost">
                    {{ csrf_field() }}

                    <input type="hidden" value="{{ $category->id }}" name="id">

                    <div class="form-group">
                        <label for="name">Name <strong class="text-danger">*</strong></label>

                        <input id="name" type="text"
                               class="form-control{{ hasInputError($errors, 'name') }}"
                               name="name" value="{{ old('name', $category->name) }}" data-validate="required">

                        @if ($errors->has('name'))
                            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="slug">Slug (Friendly URL) <strong class="text-danger">*</strong></label>

                        <input id="slug" type="text"
                               class="form-control{{ hasInputError($errors, 'slug') }}"
                               name="slug" value="{{ old('slug', $category->slug) }}" data-validate="required">

                        @if ($errors->has('slug'))
                            <div class="invalid-feedback">{{ $errors->first('slug') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="parent_id">Parent Category</label>

                        <select class="form-control select_picker"
                                data-style="btn-blue-50"
                                name="parent_id"
                                id="parent_id">
                            <option value="">Select Parent Category</option>
                            @foreach($categories as $sub)
                                <option value="{{ $sub->id }}" {{ frmIsSelected('parent_id', $sub->id, $category->parent_id) }}>{{ $sub->name }}</option>
                            @endforeach
                        </select>

                        @if ($errors->has('parent_id'))
                            <div class="invalid-feedback">{{ $errors->first('parent_id') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>

                        <textarea name="description" id="description" rows="4"
                                  class="form-control{{ hasInputError($errors, 'name') }}">{{ old('description', $category->description) }}</textarea>

                        @if ($errors->has('name'))
                            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="description">Enabled</label>

                        <label class="custom-control material-switch">
                            <span class="material-switch-control-description">No</span>
                            <input type="checkbox" class="material-switch-control-input"
                                   value="1"
                                   name="is_enabled" {{ frmIsChecked('is_enabled', 1, $category->is_enabled) }}>
                            <span class="material-switch-control-indicator"></span>
                            <span class="material-switch-control-description">Yes</span>
                        </label>

                        @if ($errors->has('is_enabled'))
                            <div class="invalid-feedback">{{ $errors->first('is_enabled') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="img_active">Active Icon</label>
                        <br>

                        <img src="{{ $category->img_active->primary }}" alt="Active Icon"
                             style="height: 64px; width: 64px;" id="_img_active"
                             class="rounded-circle shadow-sm"
                             v-on:click="onImageSelect('#img_active', '#_img_active')">
                        <input type="file" name="img_active"
                               accept="image/*"
                               id="img_active" class="form-control is-invalid d-none">
                        <small id="coverPhotoHelp" class="form-text text-muted">
                            Recommended cover photo size (64px x 64px).
                        </small>

                        @if ($errors->has('img_active'))
                            <div class="invalid-feedback">{{ $errors->first('img_active') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="img_inactive">InActive Icon</label>
                        <br>

                        <img src="{{ $category->img_inactive->primary }}" alt="Inactive Icon"
                             style="height: 64px; width: 64px;" id="_img_inactive"
                             class="rounded-circle shadow-sm"
                             v-on:click="onImageSelect('#img_inactive', '#_img_inactive')">
                        <input type="file" name="img_inactive"
                               accept="image/*"
                               id="img_inactive" class="form-control is-invalid d-none">
                        <small id="coverPhotoHelp" class="form-text text-muted">
                            Recommended cover photo size (64px x 64px).
                        </small>

                        @if ($errors->has('img_inactive'))
                            <div class="invalid-feedback">{{ $errors->first('img_inactive') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="img_banner">Banner</label>
                        <br>

                        <img src="{{ $category->img_banner->primary }}" alt="Banner"
                             style="height: 180px; width: 100%;" id="_img_banner"
                             class="rounded shadow-sm"
                             v-on:click="onImageSelect('#img_banner', '#_img_banner')">
                        <input type="file" name="img_banner"
                               accept="image/*"
                               id="img_banner" class="form-control is-invalid d-none">
                        <small id="coverPhotoHelp" class="form-text text-muted">
                            Recommended cover photo size (640px x 360px).
                        </small>

                        @if ($errors->has('img_banner'))
                            <div class="invalid-feedback">{{ $errors->first('img_banner') }}</div>
                        @endif
                    </div>

                    <hr>
                    <button class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection