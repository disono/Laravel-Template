{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

<nav class="navbar sticky-top navbar-expand-lg navbar-light shadow-sm bg-white">
    <a class="navbar-brand" href="#">{{ __settings('title')->value }}</a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item {{ isActiveMenu('module.page.home') }}">
                <a class="nav-link" href="{{ route('module.page.home') }}">Home</a>
            </li>
        </ul>

        <ul class="navbar-nav">
            @if(__me())
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ __me()->first_name }}
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('module.user.setting.general') }}">Account Settings</a>
                        <a class="dropdown-item" href="{{ route('module.user.setting.security') }}">Security</a>

                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('auth.logout') }}">Logout</a>
                    </div>
                </li>

                <li class="nav-item {{ isActiveMenu('module.user.dashboard') }}">
                    <a class="nav-link" href="{{ route('module.user.dashboard') }}">Dashboard</a>
                </li>
            @else
                <li class="nav-item {{ isActiveMenu('auth.register') }}">
                    <a class="nav-link" href="{{ route('auth.register') }}">Register</a>
                </li>
                <li class="nav-item {{ isActiveMenu('auth.login') }}">
                    <a class="nav-link" href="{{ route('auth.login') }}">Login</a>
                </li>
            @endif
        </ul>
    </div>
</nav>