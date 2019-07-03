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

                <div class="row mt-3">
                    <div class="col">
                        <form action="{{ route('admin.page.update') }}" method="post"
                              v-on:submit.prevent="onFormUpload">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{ $page->id }}">

                            <div class="row">
                                <div class="col-md-9 col-sm-12 mb-3">
                                    <div class="form-group">
                                        <label for="content">Content <strong class="text-danger">*</strong></label>
                                        <textarea name="content" id="content"
                                                  class="form-control tiny-editor-content tiny"
                                                  rows="32" placeholder="Content"
                                                  data-validate="required">{!! old('content', $page->content) !!}</textarea>

                                        @if ($errors->has('content'))
                                            <div class="invalid-feedback">{{ $errors->first('content') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-12 mb-3">
                                    <h5 class="p-0 font-weight-bold">Page Attributes</h5>
                                    <hr>

                                    <div class="form-group">
                                        <label for="name">Page Name/Title <strong class="text-danger">*</strong></label>

                                        <input id="name" type="text"
                                               class="form-control{{ hasInputError($errors, 'name') }}"
                                               name="name" value="{{ old('name', $page->name) }}"
                                               v-model="frmAdminPage.name" @change="adminPageOnNameChange"
                                               data-value="{{ old('name', $page->name) }}"
                                               data-validate="required">

                                        @if ($errors->has('name'))
                                            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="slug">Slug (Friendly URL Name) <strong
                                                    class="text-danger">*</strong></label>

                                        <input id="slug" type="text"
                                               class="form-control{{ hasInputError($errors, 'slug') }}"
                                               name="slug" value="{{ old('slug', $page->slug) }}"
                                               data-value="{{ old('slug', $page->slug) }}"
                                               v-model="frmAdminPage.slug"
                                               data-validate="required">

                                        @if ($errors->has('slug'))
                                            <div class="invalid-feedback">{{ $errors->first('slug') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="page_category_id">
                                            Category <strong class="text-danger">*</strong>
                                        </label>

                                        @foreach($categories as $category)
                                            <label class="custom-control material-checkbox">
                                                <input type="checkbox" class="material-control-input"
                                                       name="page_category_id[]"
                                                       value="{{ $category->id }}"
                                                       data-parent-id="{{ $category->parent_id }}"
                                                        {{ in_array($category->id, $classifications) ? 'checked' : '' }}>

                                                <span class="material-control-indicator"
                                                      style="margin-left: {{ $category->tab }}px;"></span>
                                                <span class="material-control-description"
                                                      style="margin-left: {{ $category->tab }}px;">
                                                    {{ $category->name }}
                                                </span>
                                            </label>
                                        @endforeach

                                        @if ($errors->has('page_category_id'))
                                            <div class="invalid-feedback">{{ $errors->first('page_category_id') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="template">Template (Custom Page Design)</label>

                                        <input id="template" type="text"
                                               class="form-control{{ hasInputError($errors, 'template') }}"
                                               name="template" value="{{ old('template', $page->template) }}">

                                        @if ($errors->has('template'))
                                            <div class="invalid-feedback">{{ $errors->first('template') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="cover_photo">Cover Photo</label>

                                        <img src="{{ $page->cover_photo->primary }}" alt="Cover Photo"
                                             id="_cover_photo"
                                             class="rounded shadow-sm img-fluid"
                                             v-on:click="onImageSelect('#cover_photo', '#_cover_photo')">
                                        <input type="file" name="cover_photo"
                                               accept="image/*"
                                               id="cover_photo" class="form-control is-invalid d-none">
                                        <small id="coverPhotoHelp" class="form-text text-muted">
                                            Recommended cover photo size (640px x 360px).
                                        </small>

                                        @if ($errors->has('cover_photo'))
                                            <div class="invalid-feedback">{{ $errors->first('cover_photo') }}</div>
                                        @endif
                                    </div>

                                    <h5 class="p-0 font-weight-bold">Publishing</h5>
                                    <hr>

                                    <div class="form-group">
                                        <label for="post_at">Post At</label>

                                        <input id="post_at" type="text"
                                               class="form-control date-picker-no-pass{{ hasInputError($errors, 'post_at') }}"
                                               name="post_at" value="{{ old('post_at', $page->formatted_post_at) }}">

                                        @if ($errors->has('post_at'))
                                            <div class="invalid-feedback">{{ $errors->first('post_at') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="expired_at">Expired At</label>

                                        <input id="expired_at" type="text"
                                               class="form-control date-picker-no-pass{{ hasInputError($errors, 'expired_at') }}"
                                               name="expired_at" value="{{ old('expired_at', $page->formatted_expired_at) }}">

                                        @if ($errors->has('expired_at'))
                                            <div class="invalid-feedback">{{ $errors->first('expired_at') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label class="custom-control material-checkbox">
                                            <input type="checkbox" class="material-control-input"
                                                   value="1"
                                                   name="is_draft" {{ frmIsChecked('is_draft', 1, $page->is_draft) }}>
                                            <span class="material-control-indicator"></span>
                                            <span class="material-control-description">Save as draft</span>
                                        </label>

                                        @if ($errors->has('is_draft'))
                                            <div class="invalid-feedback">{{ $errors->first('is_draft') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label class="custom-control material-checkbox">
                                            <input type="checkbox" class="material-control-input"
                                                   value="1"
                                                   name="is_email_to_subscriber" {{ frmIsChecked('is_email_to_subscriber', 1, $page->is_email_to_subscriber) }}>
                                            <span class="material-control-indicator"></span>
                                            <span class="material-control-description">Email to subscribers</span>
                                        </label>

                                        @if ($errors->has('is_email_to_subscriber'))
                                            <div class="invalid-feedback">{{ $errors->first('is_email_to_subscriber') }}</div>
                                        @endif
                                    </div>

                                    <h5 class="p-0 m-0 font-weight-bold">
                                        SEO Attributes
                                        <small class="font-weight-light"><a class="btn-link"
                                                                            data-toggle="collapse"
                                                                            href="#collapseSEOAttributes"
                                                                            role="button"
                                                                            aria-expanded="false"><i
                                                        class="fas fa-chevron-down"></i></a>
                                        </small>
                                    </h5>

                                    <div class="collapse border-0 p-0" id="collapseSEOAttributes">
                                        <hr>

                                        <div class="form-group">
                                            <label for="seo_description">Description</label>
                                            <textarea name="seo_description" id="seo_description"
                                                      class="form-control"
                                                      rows="3"
                                                      data-validate="max:300">{!! old('seo_description', $page->seo_description) !!}</textarea>

                                            @if ($errors->has('seo_description'))
                                                <div class="invalid-feedback">{{ $errors->first('seo_description') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="seo_keywords">Keywords</label>

                                            <input id="seo_keywords" type="text"
                                                   class="form-control{{ hasInputError($errors, 'seo_keywords') }}"
                                                   name="seo_keywords"
                                                   value="{{ old('seo_keywords', $page->seo_keywords) }}">

                                            @if ($errors->has('seo_keywords'))
                                                <div class="invalid-feedback">{{ $errors->first('seo_keywords') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="seo_robots">Robots</label>

                                            <input id="seo_robots" type="text"
                                                   class="form-control{{ hasInputError($errors, 'seo_robots') }}"
                                                   name="seo_robots" value="{{ old('seo_robots', $page->seo_robots) }}"
                                                   aria-describedby="ogImageHelp">
                                            <small id="ogImageHelp" class="form-text text-muted">
                                                A robots tag is an element in the HTML of a page that
                                                informs search engines which pages on your site should be
                                                indexed and which should not. e.g. index, follow
                                            </small>

                                            @if ($errors->has('seo_robots'))
                                                <div class="invalid-feedback">{{ $errors->first('seo_robots') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="og_url">og:url</label>

                                            <input id="og_url" type="text"
                                                   class="form-control{{ hasInputError($errors, 'og_url') }}"
                                                   name="og_url" value="{{ old('og_url', $page->og_url) }}">

                                            @if ($errors->has('og_url'))
                                                <div class="invalid-feedback">{{ $errors->first('og_url') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="og_type">og:type</label>

                                            <input id="og_type" type="text"
                                                   class="form-control{{ hasInputError($errors, 'og_type') }}"
                                                   name="og_type" value="{{ old('og_type', $page->og_type) }}">

                                            @if ($errors->has('og_type'))
                                                <div class="invalid-feedback">{{ $errors->first('og_type') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="og_title">og:title</label>

                                            <input id="og_title" type="text"
                                                   class="form-control{{ hasInputError($errors, 'og_title') }}"
                                                   name="og_title" value="{{ old('og_title', $page->og_title) }}">

                                            @if ($errors->has('og_title'))
                                                <div class="invalid-feedback">{{ $errors->first('og_title') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="og_description">og:description</label>
                                            <textarea name="og_description" id="og_description"
                                                      class="form-control"
                                                      rows="3"
                                                      data-validate="max:300">{!! old('og_description', $page->og_description) !!}</textarea>

                                            @if ($errors->has('og_description'))
                                                <div class="invalid-feedback">{{ $errors->first('og_description') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label for="og_image">og:image</label>

                                            <img src="{{ $page->og_image->primary }}" alt="og:image"
                                                 id="_og_image"
                                                 class="rounded shadow-sm img-fluid"
                                                 v-on:click="onImageSelect('#og_image', '#_og_image')">
                                            <input type="file" name="og_image"
                                                   accept="image/*"
                                                   id="og_image" class="form-control is-invalid d-none">
                                            <small id="ogImageHelp" class="form-text text-muted">
                                                Recommended cover photo size (1200 x 627) and not more than
                                                5mb.
                                            </small>

                                            @if ($errors->has('og_image'))
                                                <div class="invalid-feedback">{{ $errors->first('og_image') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <hr>
                                    <button type="submit" class="btn btn-raised btn-primary">Save Changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection