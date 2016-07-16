{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: www.webmons.com
 * License: Apache 2.0
--}}
<div id="sidebar-wrapper">
    <ul class="sidebar-nav">
        <li class="sidebar-brand">
            <a href="{{url('dashboard')}}">
                <i class="fa fa-home" aria-hidden="true"></i> Dashboard
            </a>
        </li>

        <li>
            <a href="{{url('admin/users')}}"><i class="fa fa-user" aria-hidden="true"></i> Users</a>
            <ul class="sidebar-nav-sub">
                <li>
                    <a href="{{url('admin/user/create')}}">- Create User</a>
                </li>
            </ul>
        </li>

        <li>
            <a href="{{url('admin/images')}}"><i class="fa fa-image" aria-hidden="true"></i> Images</a>
        </li>

        <li>
            <a href="{{url('admin/settings')}}"><i class="fa fa-cog" aria-hidden="true"></i> Settings</a>

            <ul class="sidebar-nav-sub">
                <li>
                    <a href="{{url('admin/authorizations')}}">- Authorization</a>

                    <ul class="sidebar-nav-sub">
                        <li>
                            <a href="{{url('admin/authorization/create')}}">- Create Authorization</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="{{url('admin/roles')}}">- Roles</a>

                    <ul class="sidebar-nav-sub">
                        <li>
                            <a href="{{url('admin/role/create')}}">- Create Role</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>
    </ul>
</div>