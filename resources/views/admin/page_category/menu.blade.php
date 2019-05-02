{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

<ul class="nav nav-tabs mt-3">
    <li class="nav-item">
        <a class="nav-link {{ isActiveMenu('admin.page.category.create') }}"
           href="{{ route('admin.page.category.create') }}"><i class="fas fa-plus"></i> Create a New Page Category</a>
    </li>
</ul>