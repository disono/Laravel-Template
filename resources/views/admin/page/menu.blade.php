{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{ isActiveMenu('admin.page.browse|admin.page.create|admin.page.edit') }}"
           href="{{ route('admin.page.browse') }}"><i class="fas fa-paperclip"></i> Pages</a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ isActiveMenu('admin.pageCategory.browse|admin.pageCategory.create|admin.pageCategory.edit') }}"
           href="{{ route('admin.pageCategory.browse') }}"><i class="fas fa-list-ul"></i> Page Categories</a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ isActiveMenu('admin.pageView.browse') }}"
           href="{{ route('admin.pageView.browse') }}"><i class="fas fa-eye"></i> Page Views</a>
    </li>
</ul>

@include('vendor.app.toolbarButtons')