{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{ isActiveMenu('admin.fcm.notification.list') }}"
           href="{{ route('admin.fcm.notification.list') }}"><i class="fas fa-bell"></i> FCM Notifications</a>
    </li>
</ul>