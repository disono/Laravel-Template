/**
 * Author: Webmons Development Studio Team (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */

var bodyParser = require('body-parser');
var expressValidator = require('express-validator');

// application routing
var routes = require('../routes/web.js');
// session
var SESSION = require('../models/mongo/session.js');

// clear the session every time the app restarts
var isClearSession = false;
if (!isClearSession) {
    isClearSession = true;
    SESSION.clear();
}

module.exports = {
    app: function (params) {
        // static public folder
        params.app.use('/public', params.express.static(params.default_path + '/public'));

        // parse application/json
        params.app.use(bodyParser.json());

        // to support URL-encoded bodies
        params.app.use(bodyParser.urlencoded({
            extended: true
        }));

        // validators
        params.app.use(expressValidator());

        // add headers
        params.app.use(function (req, res, next) {
            res.setHeader('Access-Control-Allow-Origin', '*');
            res.setHeader('Access-Control-Allow-Headers', 'Authorization, token_key, authenticated_id, X-Auth-Token, X-XSRF-TOKEN, Access-Control-Allow-Headers, Origin, Accept, X-Requested-With, Content-Type, X-CSRF-Token, Access-Control-Request-Method, Access-Control-Request-Headers');
            res.setHeader('Access-Control-Allow-Methods', 'GET, POST, PATCH, PUT, DELETE, OPTIONS, HEAD');
            res.setHeader('Access-Control-Expose-Headers', 'X-Api-Version, X-Request-Id, X-Response-Time');
            res.setHeader('Access-Control-Max-Age', '1000');

            // Pass to next layer of middleware
            next();
        });

        // JWT session middleware
        params.app.use(function (req, res, next) {
            next();
        });

        // listen to port
        params.server.listen(process.env.NODE_PORT);

        // express routes
        routes.register_express_routes(params);

        // socket routes
        params.io.on('connection', function (socket) {
            // socket routes
            params.socket = socket;
            routes.register_socket_routes(params);
        });
    }
};