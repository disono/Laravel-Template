{{--
 * @author      Archie, Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @lincense    https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

<nav class="col-md-2 d-none d-md-block bg-light sidebar">
    <div class="sidebar-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ isActiveMenu('user.dashboard.show') }}" href="{{ route('user.dashboard.show') }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ isActiveMenu('admin.user.index') }}" href="{{ route('admin.user.index') }}">
                    <i class="fas fa-users"></i> Users
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ isActiveMenu('admin.page.index') }}" href="{{ route('admin.page.index') }}">
                    <i class="fas fa-file"></i> Pages
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ isActiveMenu('admin.fcmNotification.index') }}" href="{{ route('admin.fcmNotification.index') }}">
                    <i class="fas fa-file"></i> Notifications
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ isActiveMenu('admin.file.index') }}" href="{{ route('admin.file.index') }}">
                    <i class="fas fa-upload"></i> Files
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ isActiveMenu('admin.setting.show') }}" href="{{ route('admin.setting.show') }}">
                    <i class="fas fa-cog"></i> Settings
                </a>
            </li>
        </ul>
    </div>
</nav>