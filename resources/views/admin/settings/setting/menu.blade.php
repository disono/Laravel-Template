{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

<ul class="nav nav-tabs mt-3">
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
           aria-expanded="false"><i class="fas fa-cog"></i> Manage</a>
        <div class="dropdown-menu">
            <a class="dropdown-item {{ isActiveMenu('admin.setting.list') }}" href="{{ route('admin.setting.list') }}">Settings</a>
            <a class="dropdown-item {{ isActiveMenu('admin.setting.category.list') }}"
               href="{{ route('admin.setting.category.list') }}">Setting Categories</a>
        </div>
    </li>
</ul>