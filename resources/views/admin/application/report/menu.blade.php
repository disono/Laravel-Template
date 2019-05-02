{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

<ul class="nav nav-tabs mt-3">
    <li class="nav-item">
        <a class="nav-link {{ isActiveMenu('admin.report.reason.list') }}"
           href="{{ route('admin.report.reason.list') }}"><i class="fas fa-info"></i> Reasons</a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ isActiveMenu('admin.submitted.report.list') }}"
           href="{{ route('admin.submitted.report.list') }}"><i class="fas fa-paper-plane"></i> Submitted/Reported</a>
    </li>
</ul>