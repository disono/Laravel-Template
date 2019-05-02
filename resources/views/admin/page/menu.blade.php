{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{ isActiveMenu('admin.page.list') }}"
           href="{{ route('admin.page.list') }}"><i class="fas fa-newspaper"></i> All Pages</a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ isActiveMenu('admin.page.category.list') }}"
           href="{{ route('admin.page.category.list') }}"><i class="fas fa-list-ul"></i> All Page Categories</a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ isActiveMenu('admin.page.view') }}"
           href="{{ route('admin.page.view') }}"><i class="fas fa-eye"></i> Page Views</a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ isActiveMenu('admin.page.create') }}"
           href="{{ route('admin.page.create') }}"><i class="fas fa-plus"></i> Create a New Page</a>
    </li>
</ul>

@include('admin.layouts.toolbarList')