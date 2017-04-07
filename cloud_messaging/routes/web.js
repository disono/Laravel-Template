/**
 * Author: Webmons Development Studio Team (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */

var _controller_socket_path = '../controllers/socket/';
var _controller_express_path = '../controllers/express/';

// list of routes fort socket
var _socket_routes = [
    {controller: 'main_controller'},
    {controller: 'message_controller'}
];

// list of routes for express
var _express_routes = [
    {controller: 'main_controller'}
];

function _run_socket(params) {
    for (var i = 0; i < _socket_routes.length; i++) {
        require(_controller_socket_path + _socket_routes[i].controller + '.js').constructor(params);
    }
}

function _run_path(params) {
    for (var i = 0; i < _express_routes.length; i++) {
        require(_controller_express_path + _express_routes[i].controller + '.js').constructor(params);
    }
}

module.exports = {
    // register socket routes
    register_socket_routes: function (params) {
        _run_socket(params);
    },

    // register express routes
    register_express_routes: function (params) {
        _run_path(params);
    }
};