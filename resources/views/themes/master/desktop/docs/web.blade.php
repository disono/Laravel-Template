{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

@extends(currentTheme() . 'layouts.master')

@section('content')
    <div class="container">
        <div class="row p-3 rounded shadow-sm bg-white">
            <div class="col">
                <div class="container">
                    <div class="custom-controls-stacked d-block my-3">
                        <label class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">Custom checkbox</span>
                        </label>
                    </div>

                    <div class="custom-controls-stacked d-block my-3">
                        <label class="custom-control material-checkbox">
                            <input type="checkbox" class="material-control-input">
                            <span class="material-control-indicator"></span>
                            <span class="material-control-description">Material checkbox</span>
                        </label>

                        <label class="custom-control fill-checkbox">
                            <input type="checkbox" class="fill-control-input">
                            <span class="fill-control-indicator"></span>
                            <span class="fill-control-description">Fill checkbox</span>
                        </label>

                        <label class="custom-control overflow-checkbox">
                            <input type="checkbox" class="overflow-control-input">
                            <span class="overflow-control-indicator"></span>
                            <span class="overflow-control-description">Overflow checkbox</span>
                        </label>
                    </div>

                    <div class="custom-controls-stacked d-block my-3">
                        <label class="custom-control material-switch">
                            <span class="material-switch-control-description">Off</span>
                            <input type="checkbox" class="material-switch-control-input">
                            <span class="material-switch-control-indicator"></span>
                            <span class="material-switch-control-description">On</span>
                        </label>

                        <label class="custom-control ios-switch">
                            <span class="ios-switch-control-description">Off</span>
                            <input type="checkbox" class="ios-switch-control-input">
                            <span class="ios-switch-control-indicator"></span>
                            <span class="ios-switch-control-description">On</span>
                        </label>

                        <label class="custom-control border-switch">
                            <span class="border-switch-control-description">Off</span>
                            <input type="checkbox" class="border-switch-control-input">
                            <span class="border-switch-control-indicator"></span>
                            <span class="border-switch-control-description">On</span>
                        </label>

                        <label class="custom-control teleport-switch">
                            <span class="teleport-switch-control-description">Off</span>
                            <input type="checkbox" class="teleport-switch-control-input">
                            <span class="teleport-switch-control-indicator"></span>
                            <span class="teleport-switch-control-description">On</span>
                        </label>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card my-3">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-control">
                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">Custom checkbox</span>
                                        </label>
                                    </li>

                                    <li class="list-group-control">
                                        <label class="custom-control overflow-checkbox">
                                            <input type="checkbox" class="overflow-control-input">
                                            <span class="overflow-control-indicator"></span>
                                            <span class="overflow-control-description">Overflow checkbox</span>
                                        </label>
                                    </li>

                                    <li class="list-group-control">
                                        <label class="custom-control material-checkbox">
                                            <input type="checkbox" class="material-control-input">
                                            <span class="material-control-indicator"></span>
                                            <span class="material-control-description">Material checkbox</span>
                                        </label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card my-3">
                                <div class="card-body">
                                    <h4 class="card-title m-0">Card title</h4>
                                </div>

                                <ul class="list-group list-group-flush">
                                    <li class="list-group-control">
                                        <label class="custom-control custom-radio">
                                            <input name="radio" type="radio" class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">Custom radio</span>
                                        </label>
                                    </li>

                                    <li class="list-group-control">
                                        <label class="custom-control custom-radio">
                                            <input name="radio" type="radio" class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">Custom radio</span>
                                        </label>
                                    </li>

                                    <li class="list-group-control">
                                        <label class="custom-control custom-radio">
                                            <input name="radio" type="radio" class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">Custom radio</span>
                                        </label>
                                    </li>
                                </ul>

                                <div class="card-body">
                                    <a href="#" class="btn btn-primary btn-block">Do something</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection