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
            <div class="col-md-4 col-sm-12 mt-3">
                @if(session()->has('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session()->get('success') }}
                    </div>
                @endif

                @if(session()->has('error_message'))
                    <div class="alert alert-danger" role="alert">
                        {{ session()->get('error_message') }}
                    </div>
                @endif

                <form action="{{ route('admin.csvImport.store') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="source" value="{{ $request->get('source') }}">

                    <div class="form-group">
                        <div class="custom-file">
                            <input type="file" name="csv"
                                   class="custom-file-input{{ $errors->has('csv') ? ' is-invalid invalid' : '' }}"
                                   id="csv" required>
                            <label class="custom-file-label"
                                   for="csv">Choose csv file...</label>

                            <small id="emailHelp" class="form-text text-muted">Upload on file with extension xls and
                                xlsx.
                            </small>

                            @if ($errors->has('csv'))
                                <div class="invalid-feedback">{{ $errors->first('csv') }}</div>
                            @endif
                        </div>
                    </div>

                    <hr>
                    <button type="submit" class="btn btn-primary">Import</button>
                    <a href="{{ $link }}" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection