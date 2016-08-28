{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: www.webmons.com
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

            <a class="navbar-brand" href="{{url('/')}}">
                <i class="fa fa-bars fa-lg" aria-hidden="true" id="menu-toggle"></i>
                {{app_header('title')}}
            </a>
        </div>

        <div class="collapse navbar-collapse" id="collapseMenuAdmin">
            <ul class="nav navbar-nav pull-right">
                <li>
                    <a href="{{url('user/settings')}}"><i class="fa fa-user" aria-hidden="true"></i> Profile</a>
                </li>

                <li>
                    <a href="{{url('logout')}}"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>