{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

<div class="nav flex-column nav-pills shadow-sm mb-3 p-3 rounded bg-white" id="v-pills-tab" role="tablist" aria-orientation="vertical">
    <a class="nav-link {{ isActiveMenu('user.settings') }}" href="{{ route('user.settings') }}" role="tab">
        <i class="fas fa-cog"></i> General Settings
    </a>

    <a class="nav-link {{ isActiveMenu('user.security') }}" href="{{ route('user.security') }}" role="tab">
        <i class="fas fa-lock"></i> Security
    </a>

    <a class="nav-link {{ isActiveMenu('user.setting.addresses') }}" href="{{ route('user.setting.addresses') }}" role="tab">
        <i class="fas fa-building"></i> My Address
    </a>

    <a class="nav-link" href="" role="tab">
        <i class="fas fa-phone"></i> Contact Number
    </a>

    <a class="nav-link" href="" role="tab">
        <i class="fas fa-exclamation-triangle"></i> My Reports
    </a>
</div>