{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: www.webmons.com
 * License: Apache 2.0
--}}
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#collapsableMenu" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <a class="navbar-brand" href="{{url('/')}}">{{app_header('title')}}</a>
        </div>

        <div class="collapse navbar-collapse" id="collapsableMenu">
            <ul class="nav navbar-nav">
                <li class="{{active_url(null)}}">
                    <a class="nav-link" href="{{url('/')}}">Home <span class="sr-only">(current)</span></a>
                </li>
            </ul>

            <ul class="nav navbar-nav pull-right">
                @if(auth()->check())
                    <li class="{{active_url('dashboard')}}">
                        <a class="nav-link" href="{{url('dashboard')}}">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{url('logout')}}"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
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
    </div>
</nav>