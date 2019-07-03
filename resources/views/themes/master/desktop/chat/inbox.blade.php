{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

{{-- Compose Message & Group --}}
<div class="p-3">
    <div class="row mb-3">
        <div class="col-5"><h4>Messaging</h4></div>

        <div class="col" style="text-align: right !important;">
            <button class="btn btn-primary" v-on:click="btnChatCreateGroupModal()">
                <i class="fas fa-user-friends"></i>
            </button>

            <button class="btn btn-primary" v-on:click="btnChatWriteNewMessage()"><i class="fas fa-edit"></i></button>
        </div>
    </div>

    {{-- Search Messages --}}
    <div class="row no-gutters m-0">
        <div class="col m-0 p-0">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search Messages"
                       aria-label="Search Messages" aria-describedby="button-search-message"
                       v-on:keyup="filterChatGroups($event)"
                       @keydown.enter.prevent
                       v-model="groupFilter.search">

                <div class="input-group-append">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false"><i class="fas fa-sliders-h"></i>
                    </button>

                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#" v-on:click="setChatFilterGroups(null)" @click.prevent>
                            All Messages
                        </a>
                        <a class="dropdown-item" href="#" v-on:click="setChatFilterGroups('inbox')"
                           @click.prevent>Inbox</a>
                        <a class="dropdown-item" href="#" v-on:click="setChatFilterGroups('archived')"
                           @click.prevent>Archived</a>
                        <a class="dropdown-item" href="#" v-on:click="setChatFilterGroups('unread')" @click.prevent>Unread</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Group List --}}
<div class="chat-inbox-container">
    <div class="list-group" v-if="groups.results.length">
        <a href="#" class="list-group-item list-group-item-action border-0" v-for="group in groups.results"
           v-on:click="selectChatGroup(group)" @click.prevent>
            <div class="media">
                <img v-bind:src="group.photo" class="align-self-start mr-3 chat-profile-photo-search shadow-sm">

                <div class="media-body">
                    <h5 class="mt-0 mb-0">@{{ group.group_name }}</h5>
                    <p class="mb-0 mt-0 pb-0 text-muted"
                       v-if="group.latest_message_at">
                        <small style="font-size: 11px;">@{{ group.latest_message_at }}</small>
                    </p>
                    <p class="mt-0 pt-0" v-if="group.latest_message">@{{ group.latest_message_summary }}</p>

                    <span class="badge badge-warning" v-if="group.is_spam">Spam</span>
                </div>
            </div>
        </a>
    </div>

    <p class="text-center p-0 mt-3" v-if="groups.results.length">
        <a href="#" v-on:click="loadMoreGroups()" @click.prevent>Load more...</a>
    </p>

    <div class="text-center mb-3" v-if="!groups.results.length">
        <p>No Messages.</p>
    </div>
</div>