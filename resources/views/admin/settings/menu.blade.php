{{--
 * @author      Archie, Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @lincense    https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

<nav class="nav nav-pills nav-justified mt-3">
    <a class="nav-link {{ isActiveMenu('admin.setting.show') }}"
       href="{{ route('admin.setting.show') }}"><i class="fas fa-wrench"></i> General Settings</a>

    <a class="nav-link {{ isActiveMenu('admin.role.index') }}"
       href="{{ route('admin.role.index') }}"><i class="fas fa-key"></i> Authorizations</a>

    <a class="nav-link {{ isActiveMenu('admin.activityLog.index') }}"
       href="{{ route('admin.activityLog.index') }}"><i class="fas fa-list"></i> Activity Logs</a>
</nav>