{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template
 * License: Apache 2.0
--}}
<div class="row">
    <div class="col-6 offset-md-3">
        <ul class="nav nav-pills nav-fill">
            <li class="nav-item">
                <a class="nav-link {{active_url('user/settings')}}" href="{{url('user/settings')}}">
                    <i class="fa fa-sliders" aria-hidden="true"></i> General</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{active_url('user/security')}}" href="{{url('user/security')}}">
                    <i class="fa fa-lock" aria-hidden="true"></i> Security</a>
            </li>
        </ul>
    </div>
</div>
