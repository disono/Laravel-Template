/**
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @lincense    https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
 */

Vue.use(WBProviderPlugin);

new Vue({
    el: '#WBApp',

    mounted: function () {
        // initialize libraries and non-vue codes (public/assets/js/vendor/initialize.js)
        WBInitialize();

        let self = this;
        jQ(document).ready(function () {
            self._mountedChatMessaging();
        });
    },

    data: {
        group: null,

        groups: {
            isLoading: false,
            results: [],
            page: 1
        },

        messages: {
            isLoading: false,
            isNoMsgShown: false,
            btnIsShowMore: false,
            results: [],
            page: 1
        },

        profileSearch: {
            keyword: null,
            results: []
        },

        groupFilter: {
            search: null,
            has_unread: null,
            has_archive: 0,
            page: 1
        },

        writeMessage: {
            group_id: null,
            message: null,
            files: []
        },

        createGroup: {
            name: null,
            members: [],

            searchProfileInput: null,
            profileSearch: [],

            isUpdate: false,
            btnName: 'Create',
            title: 'Create a New Group'
        }
    },

    methods: {
        // ************************************************************************
        // Helper Methods
        // ************************************************************************

        _searchProfiles(keyword) {
            return new Promise(function (resolve, reject) {
                if (!keyword) {
                    resolve([]);
                    return;
                }

                WBServices.http.get('/u/search', {search: keyword}).then(function (response) {
                    resolve(response.data);
                });
            });
        },

        _fetchInboxMsg(id, type) {
            let self = this;
            return new Promise(function (resolve, reject) {
                WBServices.http.get('/chat/inbox/' + id + '/' + type).then(function (response) {
                    self.group = response.data.group;
                    resolve(response.data);
                }).catch(reject);
            });
        },

        _fetchGroups(params) {
            let self = this;
            return WBServices.http.get('/chat/groups', params).then(function (response) {
                if (self.groups.page === 1) {
                    self.groups.results = [];
                }

                if (self.groups.results.length) {
                    self.groups.results = self.groups.results.concat(response.data);
                } else {
                    self.groups.results = response.data;
                }

                if (response.data.length) {
                    self.groups.page++;
                }

                return response;
            });
        },

        _fetchMessages(group_id) {
            let self = this;
            return WBServices.http.get('/chat/messages/' + group_id, {page: self.messages.page}).then(function (response) {
                // remove duplicates
                self.messages.results.forEach(function (msgVal) {
                    response.data.forEach(function (dataVal, dataIndex) {
                        if (msgVal.id === dataVal.id) {
                            response.data.splice(dataIndex, 1);
                        }
                    });
                });

                if (self.messages.results.length) {
                    self.messages.results = response.data.reverse().concat(self.messages.results);
                } else {
                    self.messages.results = response.data.reverse();
                }

                if (response.data.length) {
                    self.messages.results.page++;
                }

                return response.data;
            });
        },

        _mountedChatMessaging() {
            let self = this;
            let isChatInboxURI = self._isChatInboxURI();

            if (isChatInboxURI) {
                WBServices.view.loading(true);
                self.messages.isLoading = true;
                self.messages.btnIsShowMore = false;
                self.groups.page = 1;

                self._fetchInboxMsg(isChatInboxURI[3], isChatInboxURI[4]).then(function (data) {
                    return self._fetchGroups({page: self.groups.page, has_archive: 0});
                }).then(function () {
                    return self._isScrollingMsg();
                }).then(function (scrolled) {
                    return self._fetchMessages(self.group.id);
                }).then(function (response) {
                    return self._scrollToBottomMsg();
                }).finally(function () {
                    WBServices.view.loading(false);
                    self._focusMessageInput();

                    self.messages.isLoading = false;
                    self.messages.btnIsShowMore = true;
                    self.messages.isNoMsgShown = true;
                });
            } else if (jQ('meta[name="_routeName"]').attr('content') === 'module.chat.show') {
                WBServices.view.loading(true);
                self.groups.page = 1;

                self._fetchGroups({page: self.groups.page, has_archive: 0}).then(function () {

                }).finally(function () {
                    WBServices.view.loading(false);
                });
            }
        },

        _isChatInboxURI() {
            let uriPath = window.location.pathname;
            if (uriPath && jQ('meta[name="_routeName"]').attr('content') === 'module.chat.inbox') {
                return uriPath.split('/');
            }
            return false;
        },

        _isScrollingMsg() {
            let self = this;
            return new Promise(function (resolve, reject) {
                jQ('#fluxMsg').scroll(function () {
                    if (jQ(this).scrollTop() <= 0) {
                        self.messages.isLoading = true;
                        self.messages.btnIsShowMore = false;

                        self._fetchMessages(self.group.id).then(function (response) {
                            // make the scroll bar fallback 12% below
                            if (response.length) {
                                let objDiv = document.getElementById("fluxMsg");
                                objDiv.scrollTop = objDiv.scrollHeight * 0.12;
                            }
                        }).finally(function () {
                            self.messages.isLoading = false;
                            self.messages.btnIsShowMore = true;
                        });
                    }
                });

                jQ('#fluxMsg').scrollTop(jQ('#fluxMsg')[0].scrollHeight);
                resolve(true);
            });
        },

        _scrollToBottomMsg() {
            return new Promise(function (resolve, reject) {
                let objDiv = document.getElementById("fluxMsg");
                objDiv.scrollTop = objDiv.scrollHeight;
                resolve(true);
            });
        },

        _clearMsgFiles() {
            let _fileInput = '#chat_file_msg';
            document.getElementById("chat_file_msg").value = null;
            jQ(_fileInput).val(null);

            let selection = document.getElementById('chat_file_msg');
            this.writeMessage.files = selection.files;
            if (!this.writeMessage.files.length) {
                this.writeMessage.files = [];
            }
        },

        _focusMessageInput() {
            jQ('#chatMessageInput').focus();
        },

        // ************************************************************************
        // Search Profile to Message
        // ************************************************************************

        btnChatSearchProfile() {
            let self = this;
            self._searchProfiles(self.profileSearch.keyword).then(function (data) {
                self.profileSearch.results = data;
            });
        },

        btnChatSearchProfileClear(chat) {
            this.profileSearch.keyword = null;
            this.profileSearch.results = [];
        },

        onChatSearchProfile(e) {
            let self = this;
            if (!self.profileSearch.keyword) {
                self.profileSearch.results = [];
                return;
            }

            if (e.keyCode === 13) {
                self._searchProfiles(self.profileSearch.keyword).then(function (data) {
                    self.profileSearch.results = data;
                });
            }
        },

        // ************************************************************************
        // Write a message
        // ************************************************************************

        btnChatWriteNewMessage() {
            this.group = null;
            history.pushState(null, 'New Message', '/chat');
        },

        btnChatWriteMessageToUser(profile) {
            let self = this;
            self.profileSearch.keyword = null;
            self.profileSearch.results = [];
            self.messages.btnIsShowMore = false;
            self.messages.isNoMsgShown = false;
            self.messages.results = [];
            self.messages.page = 1;

            WBServices.view.loading(true);
            self._fetchInboxMsg(profile.id, 'user').then(function (response) {
                history.pushState(null, self.group.group_name, '/chat/inbox/' + self.group.id + '/group');
                return self._fetchMessages(self.group.id);
            }).then(function () {
                // fetch group list
                self.groups.page = 1;
                return self._fetchGroups({page: self.groups.page, has_archive: 0});
            }).then(function () {
                return self._scrollToBottomMsg();
            }).finally(function () {
                WBServices.view.loading(false);
                self.messages.btnIsShowMore = true;
                self.messages.isNoMsgShown = true;
            });
        },

        // ************************************************************************
        // Group
        // ************************************************************************

        btnChatCreateGroupModal() {
            this.createGroup.title = 'Create a New Group';
            this.createGroup.btnName = 'Create';
            this.createGroup.name = null;
            this.createGroup.members = [];
            this.createGroup.isUpdate = false;
            jQ('#writeGroupModal').modal('toggle');
        },

        onChatGroupSearchProfile(e) {
            let self = this;
            if (!self.createGroup.searchProfileInput) {
                self.createGroup.profileSearch = [];
                return;
            }

            if (e.keyCode === 13) {
                self._searchProfiles(self.createGroup.searchProfileInput).then(function (data) {
                    self.createGroup.profileSearch = data;
                });
            }
        },

        btnChatGroupSearchProfile() {
            let self = this;
            self._searchProfiles(self.createGroup.searchProfileInput).then(function (data) {
                self.createGroup.profileSearch = data;
            });
        },

        btnChatAddToGroupMembers(profile) {
            this.createGroup.members.push(profile);
            this.createGroup.profileSearch = [];
            this.createGroup.searchProfileInput = null;
        },

        btnChatRemoveToGroupMembers(profile, index) {
            this.createGroup.members.splice(index, 1);
        },

        btnChatMakeGroupAdmin(profile) {
            let self = this;
            profile.is_admin = 1;

            WBServices.http.get('/chat/group/admin/add/' + self.group.id + '/' + profile.member_id).then(function () {

            }).catch(function (e) {
                self.groups.isLoading = false;
                profile.is_admin = 0;
            });
        },

        btnChatRemoveGroupAdmin(profile) {
            let self = this;
            profile.is_admin = 0;

            WBServices.http.delete('/chat/group/admin/remove/' + self.group.id + '/' + profile.member_id).then(function () {

            }).catch(function (e) {
                self.groups.isLoading = false;
                profile.is_admin = 1;
            });
        },

        btnChatCreateGroupChat() {
            let self = this;
            if (self.groups.isLoading) {
                return;
            }

            // group name is required
            if (!self.createGroup.name && !self.createGroup.isUpdate) {
                swal("Oops!", 'Group name is required.', "error");
                return;
            }

            // group members is required
            if (!self.createGroup.members.length) {
                swal("Oops!", 'Please add group members.', "error");
                return;
            }

            // get only the user id for members list
            let members = [];
            self.groups.isLoading = true;
            self.createGroup.members.forEach(function (val) {
                if (typeof val.member_id !== 'undefined') {
                    members.push(val.member_id);
                } else {
                    members.push(val.id);
                }
            });

            // submit and complete the process
            function _complete(response) {
                self.groups.isLoading = false;
                self.createGroup.name = null;
                self.createGroup.members = [];
                self.createGroup.searchProfileInput = null;
                jQ('#writeGroupModal').modal('toggle');

                // set group details and uri
                self.group = response.data;
                history.pushState(null, self.group.group_name, '/chat/inbox/' + self.group.id + '/group');

                // fetch group list (refresh)
                self.groups.page = 1;
                self._fetchGroups({page: self.groups.page, has_archive: 0}).then(function () {

                });
            }

            // update current group
            if (self.createGroup.isUpdate === true) {
                WBServices.http.post('/chat/group/update', {
                    id: self.group.id,
                    name: self.createGroup.name,
                    members: members
                }).then(_complete).catch(function (e) {
                    self.groups.isLoading = false;
                    swal("Oops!", 'Failed to create a group reason: ' + e, "error");
                });

                return;
            }

            // create new group
            WBServices.http.post('/chat/group/store', {
                name: self.createGroup.name,
                members: members
            }).then(_complete).catch(function (e) {
                self.groups.isLoading = false;
                swal("Oops!", 'Failed to create a group reason: ' + e, "error");
            });
        },

        selectChatGroup(group) {
            let self = this;
            self.messages.isLoading = true;
            self.messages.btnIsShowMore = false;
            self.messages.isNoMsgShown = false;
            self.messages.results = [];
            self.messages.page = 1;
            self.group = group;
            history.pushState(null, self.group.group_name, '/chat/inbox/' + self.group.id + '/group');

            WBServices.view.loading(true, "Fetching messages...");
            self._fetchInboxMsg(self.group.id, 'group').then(function (response) {
                return self._fetchMessages(self.group.id);
            }).then(function () {
                return self._scrollToBottomMsg();
            }).then(function () {
                return self._isScrollingMsg();
            }).finally(function () {
                WBServices.view.loading(false);
                self.messages.isLoading = false;
                self.messages.btnIsShowMore = true;
                self.messages.isNoMsgShown = true;
            });
        },

        filterChatGroups(e) {
            if (e.keyCode === 13 || !this.groupFilter.search) {
                self.groups.page = 1;
                this._fetchGroups({
                    search: this.groupFilter.search,
                    has_unread: this.groupFilter.has_unread,
                    has_archive: this.groupFilter.has_archive,
                    page: self.groups.page
                }).then(function () {

                });
            }
        },

        setChatFilterGroups(filter) {
            this.groups.page = 1;
            let _filter = {
                search: this.groupFilter.search,
                has_unread: (filter === 'unread') ? 1 : null,
                has_archive: (filter === 'archived') ? 1 : null,
                page: this.groups.page
            };

            this.groupFilter.has_unread = _filter.has_unread;
            if (filter === 'archived') {
                this.groupFilter.has_archive = 1;
            } else if (filter === 'inbox') {
                this.groupFilter.has_archive = 0;
            } else {
                this.groupFilter.has_archive = null;
            }

            this._fetchGroups(_filter).then(function () {

            });
        },

        loadMoreGroups() {
            this._fetchGroups({
                search: this.groupFilter.search,
                has_unread: this.groupFilter.has_unread,
                has_archive: this.groupFilter.has_archive,
                page: self.groups.page
            }).then(function () {

            });
        },

        // ************************************************************************
        // Messages
        // ************************************************************************

        btnChatEditGroup() {
            let self = this;
            if (!self.group) {
                return;
            }

            if (self.group) {
                WBServices.http.get('/chat/group/show/' + self.group.id).then(function (response) {
                    self.createGroup.name = response.data.name;
                    self.createGroup.members = response.data.members;
                    self.createGroup.title = 'Updating (' + self.group.group_name + ')';
                    self.createGroup.btnName = 'Update';
                    self.createGroup.isUpdate = true;
                    jQ('#writeGroupModal').modal('toggle');
                });
            }
        },

        btnChatSelectFile() {
            let self = this;
            let _fileInput = '#chat_file_msg';
            jQ(_fileInput).click();

            jQ(_fileInput).off().on("change", function () {
                let selection = document.getElementById('chat_file_msg');
                self.writeMessage.files = selection.files;

                for (let i = 0; i < selection.files.length; i++) {
                    let ext = selection.files[i].name.substr(-3);
                }
            });
        },

        btnChatSend(e) {
            let self = this;

            WBServices.form.onUpload(e, function (response) {
                self._clearMsgFiles();
                self.writeMessage.message = null;
                self.messages.results.push(response.data);
                self._focusMessageInput();
            }, function (e) {

            });
        },

        btnChatClearFiles() {
            this._clearMsgFiles();
        },

        btnChatLoadMoreMsg() {
            let self = this;
            self.messages.isLoading = true;
            self.messages.btnIsShowMore = false;

            self._fetchMessages(self.group.id).then(function (response) {

            }).finally(function () {
                self.messages.isLoading = false;
                self.messages.btnIsShowMore = true;
            });
        },

        // ************************************************************************
        // Messaging Options
        // ************************************************************************

        btnChatLeaveGroup(chat) {
            WBServices.view.dialogs('chatLeaveGroup', null, function (r) {
                WBServices.http.delete('/chat/group/leave/' + chat.group.id).then(function (response) {
                    WBServices.http.redirect('/chat');
                }, function (e) {
                    WBServices.error.snackbar(e);
                });
            }, function (r) {

            });
        },

        btnChatDeleteConversation() {
            let self = this;
            WBServices.view.dialogs('chatDeleteConversation', null, function (r) {
                WBServices.http.delete('/chat/delete/conversation/' + self.group.id).then(function () {
                    self.messages.page = 1;
                    return self._fetchMessages(self.group.id)
                }).then(function () {

                }).catch(function (e) {
                    WBServices.error.snackbar(e);
                });
            }, function (r) {

            });
        },

        btnChatArchiveGroup(status) {
            let self = this;
            WBServices.http.get('/chat/group/archive/' + self.group.id + '/' + status).then(function () {
                if (status === 1) {
                    WBServices.http.redirect('/chat');
                }

                self.group.has_archive = status;
            }).catch(function (e) {
                WBServices.error.snackbar(e);
            });
        }
    }
});