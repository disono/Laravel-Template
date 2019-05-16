{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

@extends('admin.layouts.master')

@section('content')
    <div class="container-fluid shadow-sm p-3 bg-white">
        <div class="row">
            <div class="col">
                <h3>{{ $view_title }}</h3>
                <hr>
                @include('admin.settings.menu')
                @include('admin.settings.setting.menu')
            </div>
        </div>

        <form action="{{ route('admin.setting.save') }}" method="post" v-on:submit.prevent="onFormUpload">
            {{ csrf_field() }}
            @foreach((new \App\Models\Setting())->categories()->chunk(3) as $chunk)
                <div class="row mt-3">
                    @foreach ($chunk as $category)
                        <div class="col-sm-12 col-md-4">
                            <h4>{{ $category->category }}</h4>
                            <hr>

                            @foreach((new \App\Models\Setting())->fetchAll(['is_disabled' => 0, 'category' => $category->category]) as $row)
                                <div class="form-group">
                                    @if($row->input_type == 'text')
                                        <label for="formID_{{ $row->id }}">{{ $row->name }}</label>

                                        <input type="text" class="form-control" id="formID_{{ $row->id }}"
                                               name="{{ $row->key }}"
                                               aria-describedby="formHelp_{{ $row->id }}"
                                               value="{{ old($row->name, $row->value) }}">
                                    @elseif($row->input_type == 'select')
                                        <label for="formID_{{ $row->id }}">{{ $row->name }}</label>

                                        <select class="form-control select_picker" name="{{ $row->key }}"
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
                                            <label class="custom-control material-switch"
                                                   for="formID_{{ $row->id }}_{{ $_checkName }}">
                                                {{ ucfirst($val) }}<br>
                                                <span class="material-switch-control-description">Off</span>
                                                <input type="checkbox" class="material-switch-control-input"
                                                       value="{{ $val }}"
                                                       id="formID_{{ $row->id }}_{{ $_checkName }}"
                                                       name="{{ $row->key }}[]"
                                                        {{ frmIsChecked($row->key, $val, $row->value) }}>
                                                <span class="material-switch-control-indicator"></span>
                                                <span class="material-switch-control-description">On</span>
                                            </label>
                                        @endforeach
                                    @endif

                                    <small id="formHelp_{{ $row->id }}"
                                           class="form-text text-muted">{{ $row->description }}</small>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endforeach
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
@endsection