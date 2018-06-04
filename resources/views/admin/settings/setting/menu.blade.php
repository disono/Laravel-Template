{{--
 * @author      Archie, Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @lincense    https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

<nav class="nav nav-pills nav-justified mt-3">
    <a class="nav-link {{ isActiveMenu('admin.setting.index') }}"
       href="{{ route('admin.setting.index') }}"><i class="fas fa-list-ul"></i> Settings</a>

    <a class="nav-link {{ isActiveMenu('admin.setting.create') }}"
       href="{{ route('admin.setting.create') }}"><i class="fas fa-plus"></i> Add Setting</a>
</nav>