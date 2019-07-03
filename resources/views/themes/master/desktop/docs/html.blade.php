{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

<h4>Custom Checkbox</h4>
<hr>

<div class="custom-controls-stacked d-block my-3">
    <label class="custom-control material-checkbox">
        <input type="checkbox" class="material-control-input">
        <span class="material-control-indicator"></span>
        <span class="material-control-description">Material checkbox</span>
    </label>
    <div class="bg-light p-3 mt-3 mb-3 rounded-lg">
        <code>
            @php
                echo nl2br(htmlspecialchars('<label class="custom-control material-checkbox">
                        <input type="checkbox" class="material-control-input">
                        <span class="material-control-indicator"></span>
                        <span class="material-control-description">Material checkbox</span>
                    </label>'))
            @endphp
        </code>
    </div>

    <label class="custom-control fill-checkbox">
        <input type="checkbox" class="fill-control-input">
        <span class="fill-control-indicator"></span>
        <span class="fill-control-description">Fill checkbox</span>
    </label>
    <div class="bg-light p-3 mt-3 mb-3 rounded-lg">
        <code>
            @php
                echo nl2br(htmlspecialchars('<label class="custom-control fill-checkbox">
                        <input type="checkbox" class="fill-control-input">
                        <span class="fill-control-indicator"></span>
                        <span class="fill-control-description">Fill checkbox</span>
                    </label>'))
            @endphp
        </code>
    </div>

    <label class="custom-control overflow-checkbox">
        <input type="checkbox" class="overflow-control-input">
        <span class="overflow-control-indicator"></span>
        <span class="overflow-control-description">Overflow checkbox</span>
    </label>
    <div class="bg-light p-3 mt-3 mb-3 rounded-lg">
        <code>
            @php
                echo nl2br(htmlspecialchars('<label class="custom-control overflow-checkbox">
                    <input type="checkbox" class="overflow-control-input">
                    <span class="overflow-control-indicator"></span>
                    <span class="overflow-control-description">Overflow checkbox</span>
                </label>'))
            @endphp
        </code>
    </div>
</div>

<div class="custom-controls-stacked d-block my-3">
    <label class="custom-control material-switch">
        <span class="material-switch-control-description">Off</span>
        <input type="checkbox" class="material-switch-control-input">
        <span class="material-switch-control-indicator"></span>
        <span class="material-switch-control-description">On</span>
    </label>
    <div class="bg-light p-3 mt-3 mb-3 rounded-lg">
        <code>
            @php
                echo nl2br(htmlspecialchars('<label class="custom-control material-switch">
                        <span class="material-switch-control-description">Off</span>
                        <input type="checkbox" class="material-switch-control-input">
                        <span class="material-switch-control-indicator"></span>
                        <span class="material-switch-control-description">On</span>
                    </label>'))
            @endphp
        </code>
    </div>

    <label class="custom-control ios-switch">
        <span class="ios-switch-control-description">Off</span>
        <input type="checkbox" class="ios-switch-control-input">
        <span class="ios-switch-control-indicator"></span>
        <span class="ios-switch-control-description">On</span>
    </label>
    <div class="bg-light p-3 mt-3 mb-3 rounded-lg">
        <code>
            @php
                echo nl2br(htmlspecialchars('<label class="custom-control ios-switch">
                        <span class="ios-switch-control-description">Off</span>
                        <input type="checkbox" class="ios-switch-control-input">
                        <span class="ios-switch-control-indicator"></span>
                        <span class="ios-switch-control-description">On</span>
                    </label>'))
            @endphp
        </code>
    </div>

    <label class="custom-control border-switch">
        <span class="border-switch-control-description">Off</span>
        <input type="checkbox" class="border-switch-control-input">
        <span class="border-switch-control-indicator"></span>
        <span class="border-switch-control-description">On</span>
    </label>
    <div class="bg-light p-3 mt-3 mb-3 rounded-lg">
        <code>
            @php
                echo nl2br(htmlspecialchars('<label class="custom-control border-switch">
                        <span class="border-switch-control-description">Off</span>
                        <input type="checkbox" class="border-switch-control-input">
                        <span class="border-switch-control-indicator"></span>
                        <span class="border-switch-control-description">On</span>
                    </label>'))
            @endphp
        </code>
    </div>

    <label class="custom-control teleport-switch">
        <span class="teleport-switch-control-description">Off</span>
        <input type="checkbox" class="teleport-switch-control-input">
        <span class="teleport-switch-control-indicator"></span>
        <span class="teleport-switch-control-description">On</span>
    </label>
    <div class="bg-light p-3 mt-3 mb-3 rounded-lg">
        <code>
            @php
                echo nl2br(htmlspecialchars('<label class="custom-control teleport-switch">
                        <span class="teleport-switch-control-description">Off</span>
                        <input type="checkbox" class="teleport-switch-control-input">
                        <span class="teleport-switch-control-indicator"></span>
                        <span class="teleport-switch-control-description">On</span>
                    </label>'))
            @endphp
        </code>
    </div>
</div>