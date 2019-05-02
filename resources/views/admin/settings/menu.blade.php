{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{ isActiveMenu('admin.setting.show') }}"
           href="{{ route('admin.setting.show') }}"><i class="fas fa-wrench"></i> General Settings</a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ isActiveMenu('admin.role.list') }}"
           href="{{ route('admin.role.list') }}"><i class="fas fa-key"></i> Authorizations</a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ isActiveMenu('admin.activityLog.list') }}"
           href="{{ route('admin.activityLog.list') }}"><i class="fas fa-list"></i> Activity Logs</a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ isActiveMenu('admin.setting.country.list') }}"
           href="{{ route('admin.setting.country.list') }}"><i class="fas fa-building"></i> Locations</a>
    </li>
</ul>