/**
 * Author: Webmons Development Studio Team (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */

module.exports = {
    constructor: function (params) {
        /**
         * send message to specific node (message)
         */
        params.socket.on('message', function (data) {
            if (!data) {
                return;
            }

            // check if all required fields is available
            console.log('Message: ' + JSON.stringify(data));
            if (!data.to_id || !data.from_id || isNaN(data.to_id) || isNaN(data.from_id) || !data.message || !data.type) {
                return;
            }

            // send the message
            params.io.emit('message_session_' + data.to_id, data);
        });

        /**
         * send message to group (message)
         */
        params.socket.on('message_broadcast_group', function (data) {
            if (!data) {
                return;
            }

            // check if all required fields is available
            console.log('Message Broadcast Group: ' + JSON.stringify(data));
            if (!data.from_id || isNaN(data.from_id) || !data.group_id || isNaN(data.group_id) || !data.message || !data.type) {
                return;
            }

            // send the message
            params.io.emit('message_broadcast_group_' + data.group_id, data);
        });
    }
};