{{--
 * @author      Archie, Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @lincense    https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

<nav class="nav nav-pills nav-justified">
    <a class="nav-link {{ isActiveMenu('admin.user.index') }}"
       href="{{ route('admin.user.index') }}"><i class="fas fa-user-friends"></i> All Users</a>
    <a class="nav-link {{ isActiveMenu('admin.user.authentication.history') }}"
       href="{{ route('admin.user.authentication.history') }}"><i class="fas fa-history"></i> Authentication History</a>
    <a class="nav-link {{ isActiveMenu('admin.user.create') }}"
       href="{{ route('admin.user.create') }}"><i class="fas fa-user-plus"></i> Register New User</a>

    @include('vendor.menuCSV', ['csvSource' => 'users'])
</nav>