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

                @include('admin.settings.menu')
                @include('admin.settings.setting.menu')
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6 col-sm-12 offset-md-3">
                <form action="{{ route('admin.setting.save') }}" method="post" v-on:submit.prevent="onFormUpload">
                    {{ csrf_field() }}

                    @foreach($settings as $row)
                        <div class="form-group">
                            @if($row->input_type == 'text')
                                <label for="formID_{{ $row->id }}">{{ $row->name }}</label>

                                <input type="text" class="form-control" id="formID_{{ $row->id }}"
                                       name="{{ $row->key }}"
                                       aria-describedby="formHelp_{{ $row->id }}"
                                       value="{{ old($row->name, $row->value) }}">
                            @elseif($row->input_type == 'select')
                                <label for="formID_{{ $row->id }}">{{ $row->name }}</label>

                                <select class="form-control" name="{{ $row->key }}"
                                        aria-describedby="formHelp_{{ $row->id }}"
                                        id="formID_{{ $row->id }}">
                                    @foreach($row->input_value as $val)
                                        <option value="{{ $val }}" {{ frmIsSelected($row->key, $val, $row->value) }}>{{ $val }}</option>
                                    @endforeach
                                </select>
                            @elseif($row->input_type == 'checkbox')
                                <h5>{{ $row->name }}</h5>

                                @foreach($row->input_value as $val)
                                    <?php $_checkName = time() . str_random(8); ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="{{ $val }}"
                                               id="formID_{{ $row->id }}_{{ $_checkName }}"
                                               aria-describedby="formHelp_{{ $row->id }}"
                                               name="{{ $row->key }}[]" {{ frmIsChecked($row->key, $val, $row->value) }}>
                                        <label class="form-check-label" for="formID_{{ $row->id }}_{{ $_checkName }}">
                                            {{ $val }}
                                        </label>
                                    </div>
                                @endforeach
                            @endif

                            <small id="formHelp_{{ $row->id }}"
                                   class="form-text text-muted">{{ $row->description }}</small>
                        </div>
                    @endforeach

                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
@endsection