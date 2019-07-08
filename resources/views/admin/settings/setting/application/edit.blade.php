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
                        @include('admin.settings.menu')
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-sm-12 col-md-4">
                        <form action="{{ route('admin.setting.update') }}" method="post"
                              v-on:submit.prevent="onFormUpload">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{ $setting->id }}">

                            <div class="form-group">
                                <label for="name">Name <strong class="text-danger">*</strong></label>

                                <input id="name" type="text"
                                       class="form-control{{ hasInputError($errors, 'name') }}"
                                       name="name" value="{{ old('name', $setting->name) }}"
                                       data-validate="required">

                                @if ($errors->has('name'))
                                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="key">Key <strong class="text-danger">*</strong></label>

                                <input id="key" type="text"
                                       class="form-control{{ hasInputError($errors, 'key') }}"
                                       name="key" value="{{ old('key', $setting->key) }}" data-validate="required">

                                @if ($errors->has('key'))
                                    <div class="invalid-feedback">{{ $errors->first('key') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="value">Default Value</label>

                                <input id="value" type="text"
                                       class="form-control{{ hasInputError($errors, 'value') }}"
                                       aria-describedby="valueHelp"
                                       name="value" value="{{ old('value', $setting->original_value) }}">

                                <small id="valueHelp" class="form-text text-muted">Please follow this format for
                                    checkbox (Multiple Selection) ONLY "value_1,value_2" add comma (,) between value without spaces.
                                </small>
                                @if ($errors->has('value'))
                                    <div class="invalid-feedback">{{ $errors->first('value') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>

                                <textarea class="form-control{{ hasInputError($errors, 'description') }}"
                                          name="description" id="description" cols="30"
                                          rows="2">{{ old('description', $setting->description) }}</textarea>

                                @if ($errors->has('description'))
                                    <div class="invalid-feedback">{{ $errors->first('description') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="input_type">Input Type <strong class="text-danger">*</strong></label>

                                <select name="input_type"
                                        id="input_type"
                                        data-style="btn-blue-50"
                                        class="form-control select_picker{{ hasInputError($errors, 'input_type') }}"
                                        data-validate="required">
                                    <option value="text" {{ frmIsSelected('input_type', 'text', $setting->input_type) }}>
                                        Text
                                    </option>
                                    <option value="select" {{ frmIsSelected('input_type', 'select', $setting->input_type) }}>
                                        Select
                                    </option>
                                    <option value="checkbox_multiple" {{ frmIsSelected('input_type', 'checkbox_multiple', $setting->input_type) }}>
                                        Checkbox (Multiple Selection)
                                    </option>
                                    <option value="checkbox_single" {{ frmIsSelected('input_type', 'checkbox_single', $setting->input_type) }}>
                                        Checkbox (Single Selection)
                                    </option>
                                </select>

                                @if ($errors->has('input_type'))
                                    <div class="invalid-feedback">{{ $errors->first('input_type') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="input_value">Input Value</label>

                                <input id="input_value" type="text"
                                       class="form-control{{ hasInputError($errors, 'input_value') }}"
                                       name="input_value" aria-describedby="inputValueHelp"
                                       value="{{ old('input_value', $setting->original_input_value) }}">

                                <small id="inputValueHelp" class="form-text text-muted">Please follow this format
                                    for select and checkbox (Multiple Selection) ONLY "value_1,value_2" add comma (,) between value
                                    without spaces.
                                </small>
                                @if ($errors->has('input_value'))
                                    <div class="invalid-feedback">{{ $errors->first('input_value') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="attributes">Attributes</label>

                                <textarea class="form-control" name="attributes" id="attributes"
                                          cols="30"
                                          rows="2">{{ old('attributes', $setting->attributes) }}</textarea>

                                @if ($errors->has('attributes'))
                                    <div class="invalid-feedback">{{ $errors->first('attributes') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="category_setting_id">Category <strong class="text-danger">*</strong></label>

                                <select name="category_setting_id"
                                        id="category"
                                        class="form-control select_picker"
                                        data-style="btn-blue-50"
                                        data-validate="required">
                                    <option value="">Select Category</option>

                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                                {{ frmIsSelected('category_setting_id', $category->id, $setting->category_setting_id) }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @if ($errors->has('category_setting_id'))
                                    <div class="invalid-feedback">{{ $errors->first('category_setting_id') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label class="custom-control material-checkbox">
                                    <input type="checkbox" class="material-control-input"
                                           value="1"
                                           name="is_disabled" {{ frmIsChecked('is_disabled', 1, $setting->is_disabled) }}>
                                    <span class="material-control-indicator"></span>
                                    <span class="material-control-description">Temporarily disable</span>
                                </label>

                                @if ($errors->has('is_disabled'))
                                    <div class="invalid-feedback">{{ $errors->first('is_disabled') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label class="custom-control material-checkbox">
                                    <input type="checkbox" class="material-control-input"
                                           value="1"
                                           name="is_public" {{ frmIsChecked('is_public', 1, $setting->is_public) }}>
                                    <span class="material-control-indicator"></span>
                                    <span class="material-control-description">Public</span>
                                </label>

                                @if ($errors->has('is_public'))
                                    <div class="invalid-feedback">{{ $errors->first('is_public') }}</div>
                                @endif
                            </div>

                            <hr>
                            <button type="submit" class="btn btn-raised btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection