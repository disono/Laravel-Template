{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
--}}
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#collapseMenuAdmin"
                    aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <span class="navbar-brand">
                <i class="fa fa-bars fa-lg" style="cursor:pointer" aria-hidden="true" id="menu-toggle"></i>
                {{app_header('title')}}
            </span>
        </div>

        <div class="collapse navbar-collapse" id="collapseMenuAdmin">
            <ul class="nav navbar-nav pull-right">
                <li>
                    <a href="/"><i class="fa fa-home fa-lg" aria-hidden="true"></i></a>
                </li>

                <li>
                    <a href="{{url('messenger')}}"><i class="fa fa-comment fa-lg" aria-hidden="true"></i></a>
                </li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false">{{me()->full_name}} <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{url('user/settings')}}"><i class="fa fa-cog" aria-hidden="true"></i> General
                                Settings</a></li>
                        <li><a href="{{url('user/security')}}"><i class="fa fa-lock" aria-hidden="true"></i> Security
                                Settings</a></li>

                        <li role="separator" class="divider"></li>
                        <li><a href="{{url('logout')}}"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>