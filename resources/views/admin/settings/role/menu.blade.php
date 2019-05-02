{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

<ul class="nav nav-tabs mt-3">
    <li class="nav-item">
        <a class="nav-link {{ isActiveMenu('admin.role.list') }}"
           href="{{ route('admin.role.list') }}"><i class="fas fa-key"></i> User Roles</a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ isActiveMenu('admin.role.create') }}"
           href="{{ route('admin.role.create') }}"><i class="fas fa-plus"></i> Add New User Role</a>
    </li>
</ul>