{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
--}}
<ul class="nav nav-pills">
    <li class="{{active_url('user/settings')}}">
        <a role="presentation" href="{{url('user/settings')}}">
            <i class="fa fa-sliders" aria-hidden="true"></i> General</a>
    </li>
    <li class="{{active_url('user/security')}}">
        <a role="presentation" href="{{url('user/security')}}">
            <i class="fa fa-lock" aria-hidden="true"></i> Security</a>
    </li>
</ul>