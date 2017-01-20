{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
--}}
@extends('admin.layout.master')

@section('title', $title)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <h3 class="page-header">Create Product</h3>

            <form action="{{url('admin/product/store')}}" method="post" role="form"
                  enctype="multipart/form-data">
                <div class="col-xs-12 col-md-6">
                    <div class="app-container">
                        {{csrf_field()}}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name">Name*</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Name"
                                   value="{{old('name')}}">

                            @if ($errors->has('name'))
                                <span class="help-block">{{ $errors->first('name') }}</span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('sku') ? ' has-error' : '' }}">
                            <label for="sku">SKU</label>
                            <input type="text" class="form-control" name="sku" id="sku" placeholder="SKU"
                                   value="{{old('sku')}}">

                            @if ($errors->has('sku'))
                                <span class="help-block">{{ $errors->first('sku') }}</span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <label for="description">Description*</label>
                            <textarea name="description" id="description"
                                      cols="4" rows="5" class="form-control">{{old('description')}}</textarea>

                            @if ($errors->has('description'))
                                <span class="help-block">{{ $errors->first('description') }}</span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('features') ? ' has-error' : '' }}">
                            <label for="features">Features</label>
                            <textarea name="features" id="features"
                                      cols="4" rows="5" class="form-control">{{old('features')}}</textarea>

                            @if ($errors->has('features'))
                                <span class="help-block">{{ $errors->first('features') }}</span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('product_category_id') ? ' has-error' : '' }}">
                            <label for="product_category_id">Category*</label>
                            <select name="product_category_id" id="product_category_id" class="form-control">
                                <option value="">Select Category</option>
                                @foreach(\App\Models\ECommerce\ProductCategory::nestedToTabs() as $row)
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('product_category_id'))
                                <span class="help-block">{{ $errors->first('product_category_id') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-6">
                    <div class="app-container">
                        <h3 class="page-header">Images</h3>
                        {{-- images --}}
                        <div id="images">
                            <div id="images_container">
                                <div class="form-group{{ $errors->has('images.0') ? ' has-error' : '' }}">
                                    <input type="file" class="form-control" name="images[]">

                                    @if ($errors->has('images.0'))
                                        <span class="help-block">{{ $errors->first('images.0') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <p>
                            <button class="btn btn-block" id="addImage">Add Image</button>
                        </p>

                        <hr>

                        <div class="form-group {{ $errors->has('is_disabled') ? ' has-error' : '' }}">
                            <div class="checkbox">
                                <input type="checkbox" id="is_disabled" name="is_disabled"
                                       value="1" {{is_checked(old('is_disabled'), 1)}}>
                                <label for="is_disabled">
                                    Disabled/Unpublished
                                </label>
                            </div>

                            @if ($errors->has('is_disabled'))
                                <span class="help-block">{{ $errors->first('is_disabled') }}</span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('is_featured') ? ' has-error' : '' }}">
                            <div class="checkbox">
                                <input type="checkbox" id="is_featured" name="is_featured"
                                       value="1" {{is_checked(old('is_featured'), 1)}}>
                                <label for="is_featured">
                                    Featured
                                </label>
                            </div>

                            @if ($errors->has('is_featured'))
                                <span class="help-block">{{ $errors->first('is_featured') }}</span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('is_latest') ? ' has-error' : '' }}">
                            <div class="checkbox">
                                <input type="checkbox" id="is_latest" name="is_latest"
                                       value="1" {{is_checked(old('is_latest'), 1)}}>
                                <label for="is_latest">
                                    Latest
                                </label>
                            </div>

                            @if ($errors->has('is_latest'))
                                <span class="help-block">{{ $errors->first('is_latest') }}</span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('is_sale') ? ' has-error' : '' }}">
                            <div class="checkbox">
                                <input type="checkbox" id="is_sale" name="is_sale"
                                       value="1" {{is_checked(old('is_sale'), 1)}}>
                                <label for="is_sale">
                                    On Sale
                                </label>
                            </div>

                            @if ($errors->has('is_sale'))
                                <span class="help-block">{{ $errors->first('is_sale') }}</span>
                            @endif
                        </div>

                        <hr>

                        <div class="form-group {{ $errors->has('is_qty_required') ? ' has-error' : '' }}">
                            <div class="checkbox">
                                <input type="checkbox" id="is_qty_required" name="is_qty_required" value="1"
                                       {{is_checked(old('is_qty_required'), 1)}} checked>
                                <label for="is_qty_required">
                                    Requires Quantity (Inventory)
                                </label>
                            </div>

                            @if ($errors->has('is_qty_required'))
                                <span class="help-block">{{ $errors->first('is_qty_required') }}</span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('qty') ? ' has-error' : '' }}" id="quantityContainer">
                            <label for="qty">Qty</label>
                            <input type="text" class="form-control" name="qty" id="qty" placeholder="Qty"
                                   value="{{old('qty')}}">

                            @if ($errors->has('qty'))
                                <span class="help-block">{{ $errors->first('qty') }}</span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('srp') ? ' has-error' : '' }}">
                            <label for="srp">SRP (Current Price)</label>
                            <input type="text" class="form-control" name="srp" id="srp" placeholder="SRP"
                                   value="{{old('srp')}}">

                            @if ($errors->has('srp'))
                                <span class="help-block">{{ $errors->first('srp') }}</span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('srp_discounted') ? ' has-error' : '' }}">
                            <label for="srp_discounted">Old Price</label>
                            <input type="text" class="form-control" name="srp_discounted" id="srp_discounted"
                                   placeholder="Old Price"
                                   value="{{old('srp_discounted')}}">

                            @if ($errors->has('srp_discounted'))
                                <span class="help-block">{{ $errors->first('srp_discounted') }}</span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('is_draft') ? ' has-error' : '' }}">
                            <div class="checkbox">
                                <input type="checkbox" id="is_draft" name="is_draft"
                                       value="1" {{is_checked(old('is_draft'), 1)}}>
                                <label for="is_draft">
                                    Save as Draft
                                </label>
                            </div>

                            @if ($errors->has('is_draft'))
                                <span class="help-block">{{ $errors->first('is_draft') }}</span>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Create</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@include('vendor.loaders', ['scripts' => [
        'assets/js/ecommerce/product.js'
        ]])