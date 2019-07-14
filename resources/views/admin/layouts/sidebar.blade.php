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
                <div id="profileSettingsModal">
                    <a class="nav-link" href="#" title="Signed in as {{ me()->first_name }}">
                        <img src="{{ me()->profile_picture }}" alt="Profile"
                             class="rounded-circle img-fluid nav-avatar"
                             style="height: 32px; width: 32px;">
                        <small class="iconav-nav-label d-md-none">{{ me()->first_name }}</small>
                    </a>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ isActiveMenu('module.user.dashboard') }}" href="{{ route('module.user.dashboard') }}"
                   title="Dashboard" data-toggle="tooltip" data-placement="right">
                    <span data-feather="monitor"></span>
                    <small class="iconav-nav-label d-md-none">Dashboard</small>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ isActiveMenu('admin.user.browse|admin.user.create|admin.user.edit|
                admin.userTracker.browse|admin.userAuthenticationHistory.browse|admin.activityLog.browse|admin.activityLog.details') }}"
                   href="{{ route('admin.user.browse') }}" title="Manage Users" data-toggle="tooltip"
                   data-placement="right">
                    <span data-feather="users"></span>
                    <small class="iconav-nav-label d-md-none">Manage Users</small>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ isActiveMenu('admin.page.browse|admin.pageCategory.browse|
                admin.pageCategory.create|admin.pageCategory.edit|admin.pageView.browse|admin.page.create|
                admin.page.edit') }}"
                   href="{{ route('admin.page.browse') }}" title="Manage Pages" data-container="body"
                   data-toggle="tooltip"
                   data-placement="right">
                    <span data-feather="layout"></span>
                    <small class="iconav-nav-label d-md-none">Manage Pages</small>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ isActiveMenu('admin.fcmNotification.browse|admin.fcmNotification.create|admin.fcmNotification.edit|
                admin.socketIoNotification.browse|admin.socketIoNotification.create|admin.socketIoNotification.edit') }}"
                   href="{{ route('admin.fcmNotification.browse') }}" title="Manage Notifications" data-toggle="tooltip"
                   data-placement="right">
                    <span data-feather="bell"></span>
                    <small class="iconav-nav-label d-md-none">Manage Notifications</small>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ isActiveMenu('admin.file.browse') }}" href="{{ route('admin.file.browse') }}"
                   title="Manage Files" data-toggle="tooltip" data-placement="right">
                    <span data-feather="upload"></span>
                    <small class="iconav-nav-label d-md-none">Manage Files</small>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ isActiveMenu('admin.pageReport.browse|admin.pageReport.details|
                admin.submitted.report.store|admin.reportReason.browse|admin.reportReason.create|
                admin.reportReason.edit') }}"
                   href="{{ route('admin.pageReport.browse') }}"
                   title="Manage Reports" data-toggle="tooltip" data-placement="right">
                    <span data-feather="flag"></span>
                    <small class="iconav-nav-label d-md-none">Manage Reports</small>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ isActiveMenu('admin.setting.details|admin.setting.browse|admin.setting.create|
                admin.setting.edit|admin.settingCategory.browse|admin.settingCategory.create|
                admin.settingCategory.edit|admin.role.browse|admin.role.create|admin.role.edit|
                admin.authRole.edit|admin.settingCountry.browse|
                admin.settingCountry.create|admin.settingCountry.edit|admin.settingCity.browse|
                admin.settingCity.create|admin.settingCity.edit') }}"
                   href="{{ route('admin.setting.details') }}" title="Application Settings" data-toggle="tooltip"
                   data-placement="right">
                    <span data-feather="settings"></span>
                    <small class="iconav-nav-label d-md-none">Application Settings</small>
                </a>
            </li>
        </ul>
    </div>
</nav>