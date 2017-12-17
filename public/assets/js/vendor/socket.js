var WBSocket = (function () {
    var _socket_uri = _WBConfigs.socket;
    var _event_emitter = new EventEmitter();

    return {
        socket: null,
        emitter: function () {
            return _event_emitter;
        },

        uri: function () {
            return _socket_uri;
        },

        /**
         * Connect
         */
        connect: function (connectCallback, eventCallback, disconnectCallback) {
            if (!_socket_uri) {
                return;
            }

            this.socket = io.connect(_socket_uri);

            // on connect
            this.socket.on('connect', function () {
                console.log('SocketIO is Connected!');
                connectCallback();
            });

            // event
            this.socket.on('event', function (data) {
                console.log('SocketIO Event Received!');
                eventCallback();
            });

            // on disconnect
            this.socket.on('disconnect', function () {
                console.error('SocketIO Disconnected!');

                // reconnect if disconnected
                WBSocket.connect(connectCallback, eventCallback, disconnectCallback);

                disconnectCallback();
            });
        },

        /**
         * Emit data
         *
         * @param name
         * @param data
         */
        emit: function (name, data) {
            if (!this.socket) {
                this.connect();
                return;
            }

            this.socket.emit(name, data);
        },

        /**
         * On event received
         *
         * @param name
         * @param callback
         */
        on: function (name, callback) {
            if (!this.socket) {
                this.connect();
                return;
            }

            this.socket.on(name, function (data) {
                callback(data);
            });
        },

        /**
         * Disconnect
         */
        disconnect: function () {
            if (!this.socket) {
                return;
            }

            this.socket.disconnect();
            this.socket = null;
        },

        /**
         * Off listener
         * note that socket.off, socket.removeListener, socket.removeAllListeners, socket.removeEventListener are synonyms.
         *
         * @param name
         */
        off: function (name) {
            if (!this.socket) {
                return;
            }

            // to un-subscribe all listeners of an event
            this.socket.off(name);
        }
    };
}());