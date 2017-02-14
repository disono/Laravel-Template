/**
 * Author: Webmons Development Studio Team (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */
var util = require('util');
var SESSION = require('../../models/mongo/session.js');

module.exports = {
    constructor: function (params) {
        /**
         * home page
         */
        params.app.get('/', function (req, res) {
            res.sendFile(params.view_path + 'index.html');
        });

        /**
         * get the nearest users
         */
        params.app.get('/user/near', function (req, res) {
            req.checkQuery('lat', 'Lat is required.').notEmpty();
            req.checkQuery('lng', 'Lng is required.').notEmpty();
            req.checkQuery('dis', 'Distance is required.').notEmpty();

            // validate data
            var errors = req.validationErrors();
            if (errors) {
                res.status(422).json({
                    success: false,
                    errors: 'There have been validation errors: ' + util.inspect(errors)
                });

                return;
            }

            // show request paths
            console.log(JSON.stringify(req.query));

            if (req.query.all) {
                delete req.query.all;

                // fetch all no pagination
                SESSION.near_all(req.query, function (results) {
                    res.json(results);
                });
            } else {
                SESSION.near(req.query, function (results) {
                    res.json(results);
                });
            }
        });

        /**
         * get all users
         */
        params.app.get('/user/all', function (req, res) {
            // show request paths
            console.log(JSON.stringify(req.query));

            SESSION.all(req.query, function (results) {
                res.json(results);
            });
        });
    }
};