{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template
 * License: Apache 2.0
--}}

<div class="col-sm-12 col-lg-3">
    <div class="list-group" style="margin-top: 8px; margin-bottom: 12px; font-size: 12px;">
        {{-- Users --}}
        <a href="{{url('admin/users')}}"
           class="list-group-item list-group-item-action {{active_url('admin/users')}} list-group-item-dark">
            <i class="fa fa-user" aria-hidden="true"></i> Users</a>
        <a href="{{url('admin/user/create')}}"
           class="list-group-item list-group-item-action {{active_url('admin/user/create')}} list-group-item-secondary">&nbsp;&nbsp;&nbsp;Add
            New User</a>
        <a href="{{url('admin/user/map')}}"
           class="list-group-item list-group-item-action {{active_url('admin/user/map')}} list-group-item-secondary">&nbsp;&nbsp;&nbsp;Locator</a>
        @foreach(\App\Models\Role::getAll(['exclude' => ['slug' => ['admin']]]) as $row)
            <a href="{{url('admin/users?role=' . $row->slug)}}"
               class="list-group-item list-group-item-action list-group-item-secondary">&nbsp;&nbsp;&nbsp;{{$row->name}}</a>
        @endforeach

        {{-- Pages --}}
        <a href="{{url('admin/pages')}}"
           class="list-group-item list-group-item-action {{active_url('admin/pages')}} list-group-item-dark">
            <i class="fa fa-file-text-o" aria-hidden="true"></i> Manage Pages</a>
        <a href="{{url('admin/page-categories')}}"
           class="list-group-item list-group-item-action {{active_url('admin/page-categories')}} list-group-item-secondary">&nbsp;&nbsp;&nbsp;Page
            Categories</a>
        <a href="{{url('admin/subscriber')}}"
           class="list-group-item list-group-item-action {{active_url('admin/subscriber')}} list-group-item-secondary">&nbsp;&nbsp;&nbsp;Subscriber</a>
        <a href="{{url('admin/page/view')}}"
           class="list-group-item list-group-item-action {{active_url('admin/page/view')}} list-group-item-secondary">&nbsp;&nbsp;&nbsp;Page
            Activity & Views</a>

        {{-- Events --}}
        <a href="{{url('admin/events')}}"
           class="list-group-item list-group-item-action {{active_url('admin/events')}} list-group-item-dark">
            <i class="fa fa-calendar-o" aria-hidden="true"></i> Manage Events</a>
        <a href="{{url('admin/event/create')}}"
           class="list-group-item list-group-item-action {{active_url('admin/event/create')}} list-group-item-secondary">&nbsp;&nbsp;&nbsp;Create
            Event</a>

        {{-- Images --}}
        <div class="list-group-item list-group-item-action list-group-item-dark">
            <i class="fa fa-image" aria-hidden="true"></i> Media
        </div>
        <a href="{{url('admin/images')}}"
           class="list-group-item list-group-item-action {{active_url('admin/images')}} list-group-item-secondary">&nbsp;&nbsp;&nbsp;Images</a>
        <a href="{{url('admin/albums')}}"
           class="list-group-item list-group-item-action {{active_url('admin/albums')}} list-group-item-secondary">&nbsp;&nbsp;&nbsp;Albums</a>

        {{-- Messages --}}
        <a href="{{url('admin/message')}}"
           class="list-group-item list-group-item-action {{active_url('admin/message')}} list-group-item-dark">
            <i class="fa fa-envelope-o" aria-hidden="true"></i> Messages</a>

        {{-- Settings --}}
        <div class="list-group-item list-group-item-action list-group-item-dark">
            <i class="fa fa-cog" aria-hidden="true"></i> Settings
        </div>
        <a href="{{url('admin/settings')}}"
           class="list-group-item list-group-item-action {{active_url('admin/settings')}} list-group-item-secondary">&nbsp;&nbsp;&nbsp;Application
            Settings</a>

        {{-- Authorization --}}
        <a href="{{url('admin/authorizations')}}"
           class="list-group-item list-group-item-action {{active_url('admin/authorizations')}} list-group-item-dark">
            <i class="fa fa-lock" aria-hidden="true"></i> Authorization</a>
        <a href="{{url('admin/authorization/create')}}"
           class="list-group-item list-group-item-action {{active_url('admin/authorization/create')}} list-group-item-secondary">&nbsp;&nbsp;&nbsp;Create
            Authorization</a>
        <a href="{{url('admin/authorization/histories')}}"
           class="list-group-item list-group-item-action {{active_url('admin/authorization/histories')}} list-group-item-secondary">&nbsp;&nbsp;&nbsp;Authorization
            History</a>
        <a href="{{url('admin/activity')}}"
           class="list-group-item list-group-item-action {{active_url('admin/activity')}} list-group-item-secondary">&nbsp;&nbsp;&nbsp;Activity
            Logs</a>
        <a href="{{url('admin/roles')}}"
           class="list-group-item list-group-item-action {{active_url('admin/roles')}} list-group-item-secondary">&nbsp;&nbsp;&nbsp;Roles</a>
        <a href="{{url('admin/role/create')}}"
           class="list-group-item list-group-item-action {{active_url('admin/role/create')}} list-group-item-secondary">&nbsp;&nbsp;&nbsp;Create
            Role</a>
    </div>
</div>