{{--
 * @author      Archie, Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @lincense    https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

<nav class="nav nav-pills nav-justified mt-3">
    <a class="nav-link {{ isActiveMenu('admin.fcmNotification.create') }}"
       href="{{ route('admin.fcmNotification.create') }}"><i class="fas fa-plus"></i> Send new notification</a>
</nav>