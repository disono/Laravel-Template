{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

<ul class="nav nav-tabs mt-3">
    <li class="nav-item">
        <a class="nav-link {{ isActiveMenu('admin.setting.country.list') }}"
           href="{{ route('admin.setting.country.list') }}"><i class="fas fa-globe"></i> Countries</a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ isActiveMenu('admin.setting.country.create') }}"
           href="{{ route('admin.setting.country.create') }}"><i class="fas fa-plus"></i> Add New Country</a>
    </li>
</ul>