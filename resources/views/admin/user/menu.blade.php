{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{ isActiveMenu('admin.user.browse|admin.user.create|admin.user.edit') }}"
           href="{{ route('admin.user.browse') }}"><i class="fas fa-user-friends"></i> Users</a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ isActiveMenu('admin.userAuthenticationHistory.browse') }}"
           href="{{ route('admin.userAuthenticationHistory.browse') }}"><i class="fas fa-history"></i> Authentication Histories</a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ isActiveMenu('admin.userTracker.browse') }}"
           href="{{ route('admin.userTracker.browse') }}"><i class="fas fa-map"></i> Location Logs</a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ isActiveMenu('admin.activityLog.browse|admin.activityLog.details') }}"
           href="{{ route('admin.activityLog.browse') }}"><i class="fas fa-list"></i> Activity Logs</a>
    </li>
</ul>
