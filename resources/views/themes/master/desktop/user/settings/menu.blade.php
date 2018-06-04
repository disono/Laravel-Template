{{--
 * @author      Archie, Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @lincense    https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

<div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
    <a class="nav-link {{ isActiveMenu('user.settings') }}" href="{{ route('user.settings') }}" role="tab">
        <i class="fas fa-home"></i> General Settings
    </a>

    <a class="nav-link {{ isActiveMenu('user.security') }}" href="{{ route('user.security') }}" role="tab">
        <i class="fas fa-lock"></i> Security
    </a>
</div>