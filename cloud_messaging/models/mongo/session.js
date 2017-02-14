var jwt = require('jsonwebtoken');

var connection = require('../../database/connection.js');
var file_helper = require('../../helper/file.js');

// session schema
var SESSION = connection.connect_mongo({
    socket_id: String,

    user_id: String,
    full_name: String,
    role: String,

    secret_key: String,
    token_key: String,

    geo: {
        type: [Number],
        index: '2d'
    },
    ip: {type: String, default: 0},

    is_online: {type: Boolean, default: false},

    created_at: {
        type: Date,
        default: Date.now
    },
    updated_at: {
        type: Date,
        default: Date.now
    }
}, 'Session');

// remove secret keys
function _remove_secret_key(results) {
    if (!results.success) {
        return results;
    }

    if (results.data instanceof Array) {
        for (var i = 0; i < results.data.length; i++) {
            results.data[i] = results.data[i].toObject();

            delete results.data[i].secret_key;
        }
    }

    return results;
}

// near parameters clean
function _clean_params(params) {
    // 100 is equivalent to 1KM
    var distance = (params.dis) ? params.dis : 0;
    var parameters = {};
    distance = (100 * distance) / 6371;

    // geo location
    if (params.lat && params.lng) {
        parameters = {
            'geo': {
                $near: [
                    parseFloat((params.lat) ? params.lat : 0),
                    parseFloat((params.lng) ? params.lng : 0)
                ],
                $maxDistance: parseFloat(distance)
            }
        };
    }

    if (params.role) {
        parameters.role = params.role;
    }

    if (params.is_online) {
        parameters.is_online = params.is_online;
    }

    return parameters;
}

// make this available to our users in our Node applications
module.exports = {
    /**
     * All session with parameters
     *
     * @param params
     * @param callback
     */
    index: function (params, callback) {
        params = (params) ? params : {};

        SESSION.paginate(params, connection.pagination_mongo(params), function (err, results) {
            if (err) {
                file_helper.log(err);
            }

            callback(_remove_secret_key(connection.response(err, results)));
        }).sort({"created_at": -1});
    },

    /**
     * Get all
     *
     * @param params
     * @param callback
     */
    all: function (params, callback) {
        var parameters = _clean_params(params);

        SESSION.find(params, function (err, results) {
            if (err) {
                file_helper.log(err);
            }

            callback(_remove_secret_key(connection.response(err, results)));
        }).sort({"created_at": -1});
    },

    /**
     * Nearest with pagination
     *
     * @param params
     * @param callback
     * @returns {*}
     */
    near: function (params, callback) {
        var parameters = _clean_params(params);

        return SESSION.paginate(parameters, connection.pagination_options(params), function (err, results) {
            if (err) {
                file_helper.log(err);
            }

            callback(_remove_secret_key(connection.response(err, results)));
        });
    },

    /**
     * Get all nearest nodes
     *
     * @param params
     * @param callback
     * @returns {*}
     */
    near_all: function (params, callback) {
        var parameters = _clean_params(params);

        return SESSION.find(parameters, function (err, results) {
            if (err) {
                file_helper.log(err);
            }

            callback(_remove_secret_key(connection.response(err, results)));
        });
    },

    /**
     * Single data using token key
     *
     * @param token_key
     * @param callback
     */
    show: function (token_key, callback) {
        SESSION.findOne({
            token_key: token_key
        }, function (err, result) {
            if (err) {
                file_helper.log(err);
                return;
            }

            callback(_remove_secret_key(connection.response(err, result)));
        }).sort({"created_at": -1});
    },

    /**
     * Get session by socket id
     *
     * @param socket_id
     * @param callback
     */
    get_session: function (socket_id, callback) {
        SESSION.findOne({
            socket_id: socket_id
        }, function (err, result) {
            if (err) {
                file_helper.log(err);
                return;
            }

            callback(_remove_secret_key(connection.response(err, result)));
        }).sort({"created_at": -1});
    },

    /**
     * Get session by user_id
     *
     * @param user_id
     * @param callback
     */
    get_user_id: function (user_id, callback) {
        SESSION.findOne({
            user_id: user_id,
            is_online: 1
        }, function (err, result) {
            if (err) {
                file_helper.log(err);
            }

            callback(_remove_secret_key(connection.response(err, result)));
        }).sort({"created_at": -1});
    },

    /**
     * Get session by token_key
     *
     * @param token_key
     * @param callback
     */
    get_token_key: function (token_key, callback) {
        SESSION.findOne({
            token_key: token_key
        }, function (err, result) {
            if (err) {
                file_helper.log(err);
            }

            callback(_remove_secret_key(connection.response(err, result)));
        }).sort({"created_at": -1});
    },

    /**
     * Update sessions status online or offline
     *
     * @param socket_id
     * @param status
     * @param callback
     */
    is_online: function (socket_id, status, callback) {
        var thisModule = this;

        thisModule.get_session(socket_id, function (result) {
            if (result.success) {
                // update
                thisModule.update({
                    token_key: result.data.token_key,
                    is_online: status
                }, callback);
            }
        });
    },

    /**
     * Store session
     *
     * @param data
     * @param callback
     */
    store: function (data, callback) {
        var thisModule = this;

        thisModule.show(data.token_key, function (result) {
            if (result.success) {
                // update
                thisModule.update(data, callback);
            } else {
                // store
                SESSION({
                    socket_id: (data.socket_id) ? data.socket_id : null,

                    user_id: (data.id) ? data.id : null,
                    full_name: (data.full_name) ? data.full_name : null,
                    role: (data.role) ? data.role : null,

                    secret_key: (data.secret_key) ? data.secret_key : null,
                    token_key: (data.token_key) ? data.token_key : null,

                    geo: [
                        (data.lat) ? data.lat : 0,
                        (data.lng) ? data.lng : 0
                    ],
                    ip: (data.ip) ? data.ip : null,

                    is_online: true
                }).save(function (err) {
                    if (err) {
                        file_helper.log(err);
                        return;
                    }

                    console.log('Storing Session Details: ' + JSON.stringify(data));
                    callback();
                });
            }
        });
    },

    /**
     * Update session
     *
     * @param data
     * @param callback
     */
    update: function (data, callback) {
        this.show(data.token_key, function (result) {
            if (result.success) {
                var query = result.data;

                for (var p in data) {
                    var jsonResult = query.toObject();

                    if (jsonResult.hasOwnProperty(p)) {
                        // check if this key is present on update values
                        if (p != 'secret_key' || p != 'id' || p != 'token_key' || p != 'socket_id') {
                            query[p] = data[p];
                        }
                    }
                }

                // update lat and lng if available
                if (data.lat && data.lng) {
                    query.geo = [
                        data.lat,
                        data.lng
                    ];
                }

                query.save(function (err) {
                    if (err) {
                        file_helper.log(err);
                        return;
                    }

                    console.log('Updating Session Details: ' + JSON.stringify(data));
                    callback(query);
                });
            }
        });
    },

    /**
     * Delete session using socket id
     *
     * @param socket_id
     * @param callback
     */
    delete: function (socket_id, callback) {
        if (!socket_id) { return; }
        console.log('Socket ID to delete: ' + socket_id);

        SESSION.remove({socket_id: socket_id}, function (err) {
            if (err) {
                file_helper.log(err);
                return;
            }

            callback();
        });
    },

    /**
     * Destroy session using token_key
     *
     * @param token_key
     * @param callback
     */
    destroy_session: function (token_key, callback) {
        if (!token_key) { return; }

        SESSION.remove({token_key: token_key}, function (err) {
            if (err) {
                file_helper.log(err);
                return;
            }

            callback();
        });
    },

    /**
     * Clear session
     */
    clear: function () {
        SESSION.remove(function (err, p) {
            if (err) {
                file_helper.log(err);
                return err;
            } else {
                console.log('# of documents deleted Session:' + p);
            }
        });
    }
};