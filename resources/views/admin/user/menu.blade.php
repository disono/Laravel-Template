{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{ isActiveMenu('admin.user.list') }}"
           href="{{ route('admin.user.list') }}"><i class="fas fa-user-friends"></i> All Users</a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ isActiveMenu('admin.user.authentication.history') }}"
           href="{{ route('admin.user.authentication.history') }}"><i class="fas fa-history"></i> Authentication History</a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ isActiveMenu('admin.user.tracker.list') }}"
           href="{{ route('admin.user.tracker.list') }}"><i class="fas fa-map"></i> Tracker</a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ isActiveMenu('admin.user.create') }}"
           href="{{ route('admin.user.create') }}"><i class="fas fa-user-plus"></i> Register New User</a>
    </li>
</ul>
