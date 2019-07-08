{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

<div class="nav flex-column nav-pills shadow-sm mb-3 p-3 rounded bg-white" id="v-pills-tab" role="tablist"
     aria-orientation="vertical">
    <a class="nav-link {{ isActiveMenu('user.setting.general') }}" href="{{ route('user.setting.general') }}" role="tab">
        <i class="fas fa-cog"></i> General Settings
    </a>

    <a class="nav-link {{ isActiveMenu('user.setting.security') }}" href="{{ route('user.setting.security') }}" role="tab">
        <i class="fas fa-lock"></i> Security
    </a>

    <a class="nav-link {{ isActiveMenu('user.setting.address.browse|user.setting.address.create|user.setting.address.edit') }}"
       href="{{ route('user.setting.address.browse') }}" role="tab">
        <i class="fas fa-building"></i> My Address
    </a>

    <a class="nav-link {{ isActiveMenu('user.setting.phone.browse|user.setting.phone.create|user.setting.phone.edit') }}"
       href="{{ route('user.setting.phone.browse') }}" role="tab">
        <i class="fas fa-phone"></i> Contact Number
    </a>

    <a class="nav-link {{ isActiveMenu('module.pageReport.browse|module.pageReport.details') }}"
       href="{{ route('module.pageReport.browse') }}" role="tab">
        <i class="fas fa-exclamation-triangle"></i> My Reports
    </a>
</div>