{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

<ul class="nav nav-tabs mt-3">
    <li class="nav-item">
        <a class="nav-link {{ isActiveMenu('admin.reportReason.browse|admin.reportReason.create|admin.reportReason.edit') }}"
           href="{{ route('admin.reportReason.browse') }}"><i class="fas fa-info"></i> Reasons</a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ isActiveMenu('admin.pageReport.browse|admin.pageReport.details') }}"
           href="{{ route('admin.pageReport.browse') }}"><i class="fas fa-paper-plane"></i> Page Reports</a>
    </li>
</ul>