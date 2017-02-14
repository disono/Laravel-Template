/**
 * Author: Webmons Development Studio Team (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */

// configuration .env
require('dotenv').config();

var express = require('express');
var app = express();
var server = require('http').Server(app);
var io = require('socket.io')(server);
var bootstrap = require('./bootstrap/app.js');

// SQL
var db = require('./database/connection.js');
// connect to SQL database
var sql_connection = db.connect_sql();

// application
bootstrap.app({
    express: express,
    app: app,
    sql: sql_connection,
    server: server,

    view_path: __dirname + '/views/',
    default_path: __dirname,

    io: io
});