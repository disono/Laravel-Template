{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

<ul class="nav nav-tabs">
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle {{ isActiveMenu('admin.setting.details|admin.setting.browse|admin.setting.create|admin.setting.edit|
            admin.settingCategory.browse|admin.settingCategory.create|admin.settingCategory.edit') }}"
           data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
           aria-expanded="false"><i class="fas fa-wrench"></i> General Settings</a></a>
        <div class="dropdown-menu">
            <a class="dropdown-item {{ isActiveMenu('admin.setting.details') }}"
               href="{{ route('admin.setting.details') }}">General Settings</a>
            <a class="dropdown-item {{ isActiveMenu('admin.setting.browse|admin.setting.create|admin.setting.edit') }}"
               href="{{ route('admin.setting.browse') }}">Manage Settings</a>
            <a class="dropdown-item {{ isActiveMenu('admin.settingCategory.browse|admin.settingCategory.create|admin.settingCategory.edit') }}"
               href="{{ route('admin.settingCategory.browse') }}">Setting Categories</a>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ isActiveMenu('admin.role.browse|admin.role.create|admin.role.edit|admin.authRole.edit') }}"
           href="{{ route('admin.role.browse') }}"><i class="fas fa-key"></i> Roles</a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ isActiveMenu('admin.settingCountry.browse|admin.settingCountry.create|
            admin.settingCountry.edit|admin.settingCity.browse|admin.settingCity.create|admin.settingCity.edit') }}"
           href="{{ route('admin.settingCountry.browse') }}"><i class="fas fa-building"></i> Locations</a>
    </li>
</ul>