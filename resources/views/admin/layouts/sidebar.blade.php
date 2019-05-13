{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

<!-- Sidebar -->
<nav class="nav-wrapper" style="overflow-y: auto !important;">
    <a class="nav-icon-brand" href="{{ url('/') }}">
        <span data-feather="zap" class="iconav-brand-icon"></span>
    </a>

    <div class="icon-nav-slider">
        <ul class="nav nav-pills iconav-nav flex-md-column">
            <li class="nav-item">
                <div data-toggle="modal" data-target="#profileSettingsModal">
                    <a class="nav-link" href="#" title="Signed in as {{ me()->first_name }}">
                        <img src="{{ me()->profile_picture }}" alt="" class="rounded-circle img-fluid nav-avatar">
                        <small class="iconav-nav-label d-md-none">{{ me()->first_name }}</small>
                    </a>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ isActiveMenu('user.dashboard.show') }}" href="{{ route('user.dashboard.show') }}"
                   title="Overview">
                    <span data-feather="monitor"></span>
                    <small class="iconav-nav-label d-md-none">Overview</small>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ isActiveMenu('admin.user.list|admin.user.create|admin.user.edit|admin.user.tracker.list|admin.user.authentication.history') }}"
                   href="{{ route('admin.user.list') }}"
                   title="Users">
                    <span data-feather="users"></span>
                    <small class="iconav-nav-label d-md-none">Users</small>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ isActiveMenu('admin.page.list') }}" href="{{ route('admin.page.list') }}"
                   title="Pages" data-container="body">
                    <span data-feather="layout"></span>
                    <small class="iconav-nav-label d-md-none">Pages</small>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ isActiveMenu('admin.fcm.notification.list') }}"
                   href="{{ route('admin.fcm.notification.list') }}" title="FCM Notifications">
                    <span data-feather="bell"></span>
                    <small class="iconav-nav-label d-md-none">Notifications</small>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ isActiveMenu('admin.file.list') }}" href="{{ route('admin.file.list') }}"
                   title="Files">
                    <span data-feather="upload"></span>
                    <small class="iconav-nav-label d-md-none">Files</small>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ isActiveMenu('admin.submitted.report.list') }}"
                   href="{{ route('admin.submitted.report.list') }}"
                   title="Reports">
                    <span data-feather="flag"></span>
                    <small class="iconav-nav-label d-md-none">Reports</small>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ isActiveMenu('admin.setting.show') }}" href="{{ route('admin.setting.show') }}"
                   title="Settings">
                    <span data-feather="settings"></span>
                    <small class="iconav-nav-label d-md-none">Settings</small>
                </a>
            </li>
        </ul>
    </div>
</nav>

<!-- Account Settings Modal -->
<div class="modal fade" id="profileSettingsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Account</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <a href="{{ route('user.settings') }}" class="btn btn-block btn-outline-secondary">General Settings</a>
                <a href="{{ route('user.security') }}" class="btn btn-block btn-outline-secondary">Security Settings</a>
                <a href="{{ route('module.chat.show') }}" class="btn btn-block btn-outline-secondary">Inbox</a>
                <a href="{{ route('auth.logout') }}" class="btn btn-block btn-danger">Log out</a>
            </div>
        </div>
    </div>
</div>