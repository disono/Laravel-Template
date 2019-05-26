{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{ isActiveMenu('admin.setting.show|admin.setting.list|admin.setting.create|admin.setting.edit|
            admin.setting.category.list|admin.setting.category.create|admin.setting.category.edit') }}"
           href="{{ route('admin.setting.show') }}"><i class="fas fa-wrench"></i> General Settings</a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ isActiveMenu('admin.role.list|admin.role.create|admin.role.edit') }}"
           href="{{ route('admin.role.list') }}"><i class="fas fa-key"></i> Authorizations</a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ isActiveMenu('admin.activityLog.list') }}"
           href="{{ route('admin.activityLog.list') }}"><i class="fas fa-list"></i> Activity Logs</a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ isActiveMenu('admin.setting.country.list|admin.setting.country.create|
            admin.setting.country.edit|admin.setting.city.list|admin.setting.city.create|admin.setting.city.edit') }}"
           href="{{ route('admin.setting.country.list') }}"><i class="fas fa-building"></i> Locations</a>
    </li>
</ul>