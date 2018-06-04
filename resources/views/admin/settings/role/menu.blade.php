{{--
 * @author      Archie, Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @lincense    https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

<nav class="nav nav-pills nav-justified mt-3">
    <a class="nav-link {{ isActiveMenu('admin.role.create') }}"
       href="{{ route('admin.role.create') }}"><i class="fas fa-plus"></i> Add New User Role</a>
</nav>