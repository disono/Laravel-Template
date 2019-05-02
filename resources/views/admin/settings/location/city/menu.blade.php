{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

<ul class="nav nav-tabs mt-3">
    <li class="nav-item">
        <a class="nav-link {{ isActiveMenu('admin.setting.city.list') }}"
           href="{{ route('admin.setting.city.list', ['country_id' => $request->get('country_id')]) }}">
            <i class="fas fa-city"></i> Cities
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ isActiveMenu('admin.setting.city.create') }}"
           href="{{ route('admin.setting.city.create', ['country_id' => $request->get('country_id')]) }}">
            <i class="fas fa-plus"></i> Add New City
        </a>
    </li>
</ul>