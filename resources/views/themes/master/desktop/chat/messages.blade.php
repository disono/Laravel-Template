{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

<div class="m-xs-0 m-sm-0 ml-md-0 ml-lg-3 ml-xl-3 rounded shadow-sm bg-white p-3">
    {{-- Profile & Group Details --}}
    <div class="row mb-3" v-if="group">
        <div class="col-md-6 col-sm-12">
            <h3>@{{ group.group_name }}</h3>
            <small v-if="group.latest_message_at">@{{ group.latest_message_at }}</small>
        </div>
    </div>

    {{-- Write a new message --}}
    <div class="row" v-if="!group">
        <div class="col-md-12 col-sm-12">
            <h4 class="text-center">New Message</h4>

            {{-- Search Profile Form --}}
            <form action="">
                <div class="row">
                    <div class="col-12">
                        <div class="input-group mb-3">
                            {{-- Profile Keyword --}}
                            <input type="text" class="form-control" placeholder="Search Name"
                                   v-model="profileSearch.keyword"
                                   v-on:keyup="onChatSearchProfile($event)" @keydown.enter.prevent
                                   id="userSearchName">

                            <div class="input-group-append">
                                {{-- Clear Input --}}
                                <button class="btn btn-outline-secondary" type="button" v-if="profileSearch.keyword"
                                        v-on:click="btnChatSearchProfileClear()">
                                    <span aria-hidden="true">&times;</span>
                                </button>

                                {{-- Search --}}
                                <button class="btn btn-outline-secondary" type="button"
                                        v-on:click="btnChatSearchProfile()"><i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            {{-- Search List Profile --}}
            <div class="chat-profile-search-container">
                <div class="media mb-1" v-for="profile in profileSearch.results"
                     v-if="profileSearch.keyword && profileSearch.results.length">
                    <img v-bind:src="profile.profile_picture" class="align-self-center mr-3 chat-profile-photo-search"
                         v-bind:alt="profile.full_name">

                    <div class="media-body">
                        <p class="mt-0 chat-profile-name-search">@{{ profile.full_name }}</p>
                        <p><a href="#" class="small"
                              v-on:click="btnChatWriteMessageToUser(profile)"
                              @click.prevent>Write a message</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Messaging Forms and Message List --}}
    <div v-if="group" class="m-0">
        {{-- Messages List --}}
        <div class="chat-message-container" id="fluxMsg">
            {{-- loading --}}
            <div class="d-flex justify-content-center mt-3 mb-3 text-primary" v-if="messages.isLoading">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>

            <p class="text-center p-0 mt-3 mb-3" v-if="messages.results.length && messages.btnIsShowMore">
                <a href="#" v-on:click="btnChatLoadMoreMsg()" @click.prevent>Load more messages...</a>
            </p>

            <div class="media chat-message-text w-75" v-for="msg in messages.results">
                <img v-bind:src="msg.profile_picture"
                     class="rounded mr-3 chat-profile-photo shadow-sm"
                     v-bind:alt="msg.sender_full_name">

                <div class="media-body mb-3">
                    <div class="alert alert-chat border-0 shadow-sm mb-3">
                        <h6 class="mt-0 mb-0">@{{ msg.sender_full_name }}</h6>

                        <div class="mb-0">
                            <small class="text-muted">@{{ msg.formatted_created_at }}</small>
                            <p class="p-0 m-0">@{{ msg.message }}</p>
                        </div>
                    </div>

                    <div class="p-0 m-0">
                        <div class="row" v-for="msgFile in WBHelper.arrays.chunk(msg.files.photo, 2)">
                            <div class="col-2" v-for="imgFile in msgFile">
                                <a v-bind:href="msgFile.path" target="_blank">
                                    <img v-bind:src="imgFile.cover" class="h-100 w-100 rounded">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="messages.results.length <= 0 && messages.isNoMsgShown" class="text-center mt-3 mb-3">
                <p class="p-0 mb-1"><i class="far fa-sad-cry fa-4x"></i></p>
                <p class="p-0 m-0">No Messages.</p>
            </div>
        </div>

        {{-- Form for sending messages --}}
        <form method="POST" class="p-3 shadow-sm rounded m-0 fixed-bottom rounded shadow-sm bg-white" action="{{ route('module.chat.send') }}"
              v-on:submit.prevent="btnChatSend($event)" style="background-color: #F8F9FA;">
            <input type="file" id="chat_file_msg" name="file_msg[]" multiple style="display: none !important;">
            <input type="hidden" value="@{{ group.id }}" v-model="group.id" name="chat_group_id">

            <div class="row mt-3">
                <div class="col">
                    <div class="form-group">
                        <input type="text" class="form-control"
                               name="message" id="chatMessageInput"
                               v-model="writeMessage.message" data-validate="required"
                               @keyup.enter.native="btnChatSend($event)"
                               placeholder="Type a message...">
                    </div>
                </div>
            </div>

            <div class="row">
                <h5 class="col-12 mt-3 mt-0" v-if="writeMessage.files.length">Files:
                    <small><a href="#" class="float-right" v-on:click="btnChatClearFiles()" @click.prevent>Remove
                            All</a></small>
                </h5>

                <div class="col-12 chat-message-file-container" v-if="writeMessage.files.length">
                    <div v-for="file in writeMessage.files" class="alert alert-light border-0">
                        <strong>@{{ file.name }}</strong> <br>
                        <small>
                            @{{ Math.round((file.size / 1000)).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,') }}
                            KB
                        </small>
                    </div>
                </div>

                <div class="col-6">
                    <div class="btn-group" role="group" aria-label="Toolbar">
                        <div class="dropdown">
                            <button class="btn btn-secondary" type="button" id="chatDropdownMenuCog"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-cog"></i>
                            </button>

                            <div class="dropdown-menu" aria-labelledby="chatDropdownMenuCog">
                                <a class="dropdown-item" href="#" v-on:click="btnChatEditGroup()" @click.prevent>
                                    <i class="fas fa-users"></i> Members
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" v-on:click="btnChatLeaveGroup()" @click.prevent>
                                    <i class="fas fa-sign-out-alt"></i> Leave Group
                                </a>
                                <a class="dropdown-item" href="#" v-on:click="btnChatDeleteConversation()"
                                   @click.prevent>
                                    <i class="fas fa-trash-alt"></i> Delete Conversation
                                </a>

                                <a class="dropdown-item" href="#" v-if="!group.has_archive"
                                   v-on:click="btnChatArchiveGroup(1)" @click.prevent>
                                    <i class="fas fa-archive"></i> Archive
                                </a>
                                <a class="dropdown-item" href="#" v-if="group.has_archive"
                                   v-on:click="btnChatArchiveGroup(0)" @click.prevent>
                                    <i class="fas fa-file-archive"></i> Unarchived
                                </a>

                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" v-on:click="onReportResource()" @click.prevent>
                                    <i class="fas fa-exclamation-triangle"></i> Report
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="btn-group" role="group" aria-label="Toolbar">
                        <button class="btn btn-secondary" type="button"
                                v-on:click="btnChatSelectFile()" @click.prevent><i class="fas fa-paperclip"></i>
                        </button>
                    </div>
                </div>

                <div class="col-6">
                    <button class="btn btn-primary float-right" type="submit">Send</button>
                </div>
            </div>
        </form>
    </div>
</div>