{{--
 * @author      Archie, Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @lincense    https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

<nav class="nav nav-pills nav-justified">
    <a class="nav-link {{ isActiveMenu('admin.page.index') }}"
       href="{{ route('admin.page.index') }}"><i class="fas fa-newspaper"></i> All Pages</a>

    <a class="nav-link {{ isActiveMenu('admin.pageCategory.index') }}"
       href="{{ route('admin.pageCategory.index') }}"><i class="fas fa-list-ul"></i> All Page Categories</a>

    <a class="nav-link {{ isActiveMenu('admin.pageView.index') }}"
       href="{{ route('admin.pageView.index') }}"><i class="fas fa-eye"></i> Page Views</a>

    <a class="nav-link {{ isActiveMenu('admin.page.create') }}"
       href="{{ route('admin.page.create') }}"><i class="fas fa-plus"></i> Create a New Page</a>
</nav>

@include('admin.layouts.toolbarList')