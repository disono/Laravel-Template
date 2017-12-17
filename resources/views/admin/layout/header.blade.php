{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template
 * License: Apache 2.0
--}}

<nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
    <a class="navbar-brand" href="{{url('/')}}" id="sidebarCollapse">{{app_header('title')}}</a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarAdminContent"
            aria-controls="navbarAdminContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarAdminContent">
        <ul class="navbar-nav mr-auto"></ul>

        <ul class="navbar-nav">
            @if(auth()->check())
                <li><a href="{{url('dashboard')}}" class="nav-link">Dashboard</a></li>
                <li><a href="/" class="nav-link"><i class="fa fa-home fa-lg" aria-hidden="true"></i></a></li>
                <li><a href="{{url('messenger')}}" class="nav-link"><i class="fa fa-comment fa-lg"
                                                                       aria-hidden="true"></i></a></li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{me()->full_name}}
                    </a>

                    <div class="dropdown-menu">
                        <a href="{{url('user/settings')}}"
                           class="dropdown-item"><i class="fa fa-cog" aria-hidden="true"></i> General Settings</a>
                        <a href="{{url('user/security')}}"
                           class="dropdown-item"><i class="fa fa-lock" aria-hidden="true"></i> Security Settings</a>

                        <a href="{{url('logout')}}"
                           class="dropdown-item"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
                    </div>
                </li>
            @endif
        </ul>
    </div>
</nav>