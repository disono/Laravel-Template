/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
 */

jQ(document).ready(function () {
    // inbox
    var is_inbox_loading = false;
    var page_inbox = 1;
    var view_inbox = '';

    var reading_from_id = 0;

    // reading messages
    var is_reading_loading = false;
    var page_reading = 1;
    var view_reading = '';

    var is_sending = false;

    jQ('#btn_reading_load').hide();
    jQ('#footer_message').hide();

    /**
     * Load more data on inbox
     */
    jQ('#btn_inbox_load').off().on('click', function (e) {
        e.preventDefault();
        inbox();
    });

    /**
     * Inbox
     *
     * @param callback
     */
    function inbox(callback) {
        console.log('Loading Inbox.');

        if (is_inbox_loading) {
            return;
        }

        WBHelper.ajax({
            url: '/message/inbox',
            data: {
                page: parseInt(page_inbox)
            },
            type: 'get',
            beforeSend: function () {
                is_inbox_loading = true;
            },
            success: function (response) {
                if (response.data.length) {
                    temp_view_inbox_formatter(response.data);
                    page_inbox++;
                } else if (page_inbox == 1) {
                    jQ('#inbox_container').html('<h4 class="text-center">Inbox is Empty.</h4>');
                }
            },
            complete: function () {
                is_inbox_loading = false;

                if (callback) {
                    callback();
                }
            }
        });
    }

    /**
     * Read the inbox
     */
    function read_inbox() {
        jQ('.read_inbox').off().on('click', function (e) {
            e.preventDefault();
            var me = jQ(this);

            // loading button for reading
            jQ('#btn_reading_load').hide();
            jQ('#footer_message').hide();

            // set the value from id
            reading_from_id = me.attr('data-from-id');
            console.log('FromID: ' + reading_from_id);
            jQ('#inbox_from_id').val(reading_from_id);
            page_reading = 1;

            view_reading = '';
            jQ('#reading_container').html('<h4 class="text-center">Loading messages...</h4>');

            if (typeof (history.pushState) != "undefined") {
                if (window.history.replaceState) {
                    // prevents browser from storing history with each change:
                    window.history.replaceState(null, '', '/message/reading/' + reading_from_id);
                }
            }

            reading_messages(reading_from_id);
        });
    }

    /**
     * Inbox view template
     *
     * @param data
     */
    function temp_view_inbox_formatter(data) {
        view_inbox = '';

        for (var i = 0; i < data.length; i++) {
            console.log(data[i]);

            view_inbox += '<div class="media read_inbox" data-from-id="' + data[i].from_id + '"> \
                    <div class="media-left"> \
                        <a href="#"> \
                            <img class="media-object" src="' + data[i].from_avatar + '" alt="' + data[i].from_full_name + '" style="width: 64px!important;"> \
                        </a> \
                    </div> \
                    \
                    <div class="media-body"> \
                        <h4 class="media-heading">' + data[i].from_full_name + '</h4> \
                        ' + data[i].limit_message + ' \
                        <p><small>' + data[i].formatted_created_at + '</small></p>\
                    </div> \
                </div>';
        }

        if (page_inbox > 1) {
            jQ('#inbox_container').append(view_inbox);
        } else {
            jQ('#inbox_container').html(view_inbox);
        }

        read_inbox();
    }

    // load the inbox
    inbox(function () {
        // load the inbox from if input exists
        reading_from_id = jQ('#inbox_from_id').val();

        if (reading_from_id != 0 && reading_from_id) {
            jQ('#reading_container').html('<h4 class="text-center">Loading messages...</h4>');

            reading_messages(reading_from_id);
        } else {
            jQ('#reading_container').html('<h4 class="text-center">Please select message\'s from Inbox.</h4>');
        }
    });

    /**
     * Load more messages from
     */
    jQ('#btn_reading_load').off().on('click', function (e) {
        e.preventDefault();

        reading_messages(jQ('#inbox_from_id').val());
    });

    /**
     * Reading messages
     *
     * @param from_id
     */
    function reading_messages(from_id) {
        console.log('Reading Message from: ' + from_id);

        if (is_reading_loading) {
            return;
        }

        WBHelper.ajax({
            url: '/message/reading/' + from_id,
            data: {
                page: page_reading
            },
            type: 'get',
            beforeSend: function () {
                is_reading_loading = true;
            },
            success: function (response) {
                jQ('#footer_message').show();

                if (response.data.length) {
                    jQ('#btn_reading_load').show();

                    temp_view_reading_formatter(response.data);
                    page_reading++;
                } else if (page_reading == 1) {
                    jQ('#reading_container').html('<h4 class="text-center">No Messages.</h4>');
                }
            },
            complete: function () {
                is_reading_loading = false;
            }
        });
    }

    /**
     * Template view for list of messages from
     *
     * @param data
     */
    function temp_view_reading_formatter(data) {
        view_reading = '';

        for (var i = data.length - 1; i >= 0; i--) {
            var content = data[i];
            console.log(data[i]);

            temp_view_reading_content(content);
        }

        if (view_reading == '') {
            return;
        }

        if (page_reading > 1) {
            jQ('#reading_container').prepend(view_reading);

            var objDiv = document.getElementById("reading_container");
            objDiv.scrollTop = 0;
        } else {
            jQ('#reading_container').html(view_reading);
            to_bottom_content();
        }
    }

    /**
     * Content type
     *
     * @param content
     */
    function temp_view_reading_content(content) {
        var body = view_body_type(content);

        if (content.sender_type == 'you') {
            view_reading += '<div class="media msg_content" data-id="' + content.id + '"> \
                        <div class="media-left"> \
                            <a href="#"> \
                                <img class="media-object" src="' + content.from_avatar + '" alt="' + content.from_full_name + '" style="width: 64px!important;"> \
                            </a> \
                        </div> \
                        \
                        <div class="media-body"> \
                            <h4 class="media-heading">' + content.from_full_name + '</h4> \
                            <p><small>' + content.formatted_created_at + '</small></p>\
                            ' + body + ' \
                        </div> \
                    </div>';
        } else {
            view_reading += '<div class="media msg_content" data-id="' + content.id + '"> \
                        <div class="media-body" style="text-align: right !important;"> \
                            <h4 class="media-heading">' + content.from_full_name + '</h4> \
                            <p><small>' + content.formatted_created_at + '</small></p>\
                            ' + body + ' \
                        </div> \
                        \
                        <div class="media-right"> \
                            <a href="#"> \
                                <img class="media-object" src="' + content.from_avatar + '" alt="' + content.from_full_name + '" style="width: 64px!important;"> \
                            </a> \
                        </div> \
                    </div>';
        }
    }

    /**
     * Type of content for message
     *
     * @param content
     */
    function view_body_type(content) {
        var body = content.message;
        if (content.type == 'image') {
            body = '<img src="' + content.file + '">';
        } else if (content.type == 'video') {
            body = '<video width="320" height="240" controls><source src="' + content.file + '" type="video/mp4">Your browser does not support the video tag.</video>';
        } else if (content.type == 'file') {
            body = '<a href="' + content.file + '" target="_blank">Download File - ' + content.message + '</a>';
        } else if (content.type == 'doc') {
            body = '<a href="' + content.file + '" target="_blank">Open/View Document - ' + content.message + '</a>';
        }

        return body;
    }

    /**
     * Scroll to bottom
     */
    function to_bottom_content() {
        setTimeout(function () {
            var objDiv = document.getElementById("reading_container");
            objDiv.scrollTop = objDiv.scrollHeight;
        }, 100);
    }

    /**
     * Append latest content of a message
     *
     * @param response
     */
    function append_latest_content(response) {
        view_reading = '';
        temp_view_reading_content(response.data);

        jQ('#reading_container').append(view_reading);
    }

    /**
     * Send text message
     */
    jQ('#btn_chat_text').off().on('click', function (e) {
        e.preventDefault();
        var btn_chat_text_input = jQ('#btn_chat_text_input');

        if (btn_chat_text_input.val() && reading_from_id && !is_sending) {
            is_sending = true;
            console.log('Sending Text Message: ' + btn_chat_text_input.val());

            WBHelper.ajax({
                url: '/message/send/' + reading_from_id,
                data: {
                    message: btn_chat_text_input.val()
                },
                success: function (response) {
                    btn_chat_text_input.val('');

                    append_latest_content(response);
                    to_bottom_content();
                },
                complete: function () {
                    is_sending = false;
                }
            });
        }
    });

    /**
     * Upload
     */
    jQ('#btn_chat_upload').off().on('click', function (e) {
        e.preventDefault();

        jQ('#file_upload_message').click();
    });

    // start to upload
    jQ('#file_upload_message').on('change', function () {
        var file = jQ('#file_upload_message').prop('files');
        if (!file || !file[0]) {
            console.debug('No file selected.');
            return;
        }

        // upload file
        WBHelper.upload('/message/send/' + reading_from_id, {
            files: {msg_file: file[0]}
        }, function () {
            // before sending
        }, function (response) {
            // reset the input
            jQ('#file_upload_message').val('');

            append_latest_content(response);
            to_bottom_content();
        }, function (res) {
            // error
            jQ('#file_upload_message').val('');
        });
    });

    // message received
    WBSocket.emitter().addListener('msg_received', function (received) {
        // make sure the received message is the one we are reading
        if (received.from_id == reading_from_id) {
            temp_view_reading_content(received);

            jQ('#reading_container').append(view_reading);
            to_bottom_content();
        }
    });
});