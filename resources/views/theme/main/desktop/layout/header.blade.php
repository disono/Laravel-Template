{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template
 * License: Apache 2.0
--}}

<nav class="navbar navbar-expand-lg fixed-top navbar-light bg-light">
    <a class="navbar-brand" href="{{url('/')}}">{{app_header('title')}}</a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item {{active_url(null)}}">
                <a class="nav-link" href="{{url('/')}}">Home <span class="sr-only">(current)</span></a>
            </li>

            {{-- categories and pages--}}
            {!! \App\Models\PageCategory::categoryMenu([1]) !!}
        </ul>

        <ul class="navbar-nav">
            @if(auth()->check())
                <li class="{{active_url('dashboard')}}">
                    <a class="nav-link" href="{{url('dashboard')}}">Dashboard</a>
                </li>

                <li class="{{active_url('/')}}">
                    <a class="nav-link" href="{{url('/')}}"><i
                                class="fa fa-home fa-lg" aria-hidden="true"></i></a>
                </li>

                <li>
                    <a class="nav-link" href="{{url('messenger')}}"><i
                                class="fa fa-comment fa-lg" aria-hidden="true"></i></a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="http://example.com"
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
            @else
                <li class="{{active_url('login')}}">
                    <a class="nav-link" href="{{url('login')}}">Login</a>
                </li>
                <li class="{{active_url('register')}}">
                    <a class="nav-link" href="{{url('register')}}">Register</a>
                </li>
            @endif
        </ul>
    </div>
</nav>