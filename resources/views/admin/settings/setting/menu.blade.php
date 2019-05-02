{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

<ul class="nav nav-tabs mt-3">
    <li class="nav-item">
        <a class="nav-link {{ isActiveMenu('admin.setting.list') }}"
           href="{{ route('admin.setting.list') }}"><i class="fas fa-list-ul"></i> Settings</a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ isActiveMenu('admin.setting.create') }}"
           href="{{ route('admin.setting.create') }}"><i class="fas fa-plus"></i> Add Setting</a>
    </li>
</ul>