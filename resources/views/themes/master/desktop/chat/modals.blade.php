{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

{{-- Create a new group modal --}}
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="writeGroupLabelModal" id="writeGroupModal"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="writeGroupLabelModal">@{{ createGroup.title }}</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="">
                    <div class="row">
                        <div class="col-12">
                            {{-- Group Name --}}
                            <input type="text" class="form-control" placeholder="Your Group Name"
                                   v-model="createGroup.name">

                            <div class="row mt-3">
                                <div class="col-md-8 col-sm-12">
                                    <div class="input-group">
                                        {{-- Search Profile to Add onto Group --}}
                                        <input type="text" class="form-control" placeholder="Search for People to Add"
                                               v-model="createGroup.searchProfileInput"
                                               v-on:keyup="onChatGroupSearchProfile($event)"
                                               @keydown.enter.prevent>

                                        <div class="input-group-append">
                                            {{-- Search --}}
                                            <button class="btn btn-primary" type="button"
                                                    v-on:click="btnChatGroupSearchProfile()"><i
                                                        class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>

                                    {{-- List of profiles --}}
                                    <div class="chat-profile-search-container mt-3">
                                        <div class="media mb-2"
                                             v-for="(profile, index) in createGroup.profileSearch"
                                             v-if="createGroup.searchProfileInput && createGroup.profileSearch.length">
                                            <img v-bind:src="profile.profile_picture"
                                                 class="align-self-center mr-3 chat-profile-photo-search"
                                                 v-bind:alt="profile.full_name">

                                            <div class="media-body">
                                                <div class="mt-0">
                                                    @{{ profile.full_name }}
                                                    <br><a href="#" class="small"
                                                           v-on:click="btnChatAddToGroupMembers(profile)"
                                                           @click.prevent>Add</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12">
                                    <h5>Selected (@{{ createGroup.members.length }})</h5>

                                    {{-- Member list --}}
                                    <div class="chat-profile-search-container mt-3"
                                         v-if="createGroup.members.length">
                                        <div class="media mb-2" v-for="(profile, index) in createGroup.members">
                                            <img v-bind:src="profile.profile_picture"
                                                 class="align-self-center mr-3 chat-profile-photo-search"
                                                 v-bind:alt="profile.full_name">

                                            <div class="media-body">
                                                <div class="mt-0">
                                                    @{{ profile.full_name }}

                                                    <span v-if="!profile.is_me">
                                                        <br>
                                                        <a href="#" class="small text-danger"
                                                           v-on:click="btnChatRemoveToGroupMembers(profile, index)"
                                                           @click.prevent>Remove</a>

                                                        <span v-if="group && createGroup.isUpdate && profile.is_admin === 0 && group.is_admin">
                                                            <a href="#" class="small"
                                                               v-on:click="btnChatMakeGroupAdmin(profile)"
                                                               @click.prevent>Make Admin</a>
                                                        </span>

                                                        <span v-if="group && createGroup.isUpdate && profile.is_admin === 1 && group.is_admin">
                                                            <a href="#" class="small text-danger"
                                                               v-on:click="btnChatRemoveGroupAdmin(profile)"
                                                               @click.prevent>Remove Admin</a>
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary"
                        v-on:click="btnChatCreateGroupChat()"
                        @click.prevent>@{{ createGroup.btnName }}
                </button>
            </div>
        </div>
    </div>
</div>