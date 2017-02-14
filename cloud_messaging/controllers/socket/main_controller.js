/**
 * Author: Webmons Development Studio Team (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */

// session
var SESSION = require('../../models/mongo/session.js');

var allClients = [];

module.exports = {
    constructor: function (params) {
        var _socket_id = params.socket.id;
        console.log('Connection Establish: ' + params.socket.id);
        allClients.push(_socket_id);
        console.log(allClients);

        /**
         * every user must send this type of connection
         */
        params.socket.on('register_session', function (data) {
            if (!data) { return; }
            console.log('Registered Socket ID: ' + params.socket.id + ' Identifier ID: ' + data.id);

            // all fields is required before storing
            if (!data.id || !data.role || !data.secret_key || !data.token_key || !data.lat || !data.lng) {
                return;
            }

            // add ip and socket id
            data.ip = params.socket.request.connection.remoteAddress;
            data.socket_id = _socket_id;

            // store session
            SESSION.store(data, function () {
                console.log('Session Registered Identifier ID: ' + data.id);
            });
        });

        /**
         * destroy session using token key
         */
        params.socket.on('destroy_session', function (data) {
            if (!data) { return; }

            // destroy session
            SESSION.destroy_session(data.token_key, function () {
                console.log('Destroy Session using token_key: ' + data.token_key);
            });
        });

        /**
         * disconnected session using socket id
         */
        params.socket.on('disconnect', function () {
            // delete session
            // params.socket.id

            console.log('Disconnected: ' + _socket_id);

            SESSION.delete(_socket_id, function () {
                console.log('Deleted Session using socket id: ' + _socket_id);
            });

            var i = allClients.indexOf(_socket_id);
            allClients.splice(i, 1);
        });
    }
};