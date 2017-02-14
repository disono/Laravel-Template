/**
 * Author: Webmons Development Studio Team (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */

var mysql = require('mysql');
var createMySQLWrap = require('mysql-wrap');
var mongoose = require('mongoose');
var mongoosePaginate = require('mongoose-paginate');

module.exports = {
    /**
     * SQL
     */
    connect_sql: function () {
        // db connection
        var connection = mysql.createConnection({
            host: process.env.DB_HOST,
            user: process.env.DB_USERNAME,
            password: process.env.DB_PASSWORD,
            database: process.env.DB_DATABASE
        });

        console.log('MySQL Connected.');

        // node-mysql-wrap constructor
        return createMySQLWrap(connection);
    },

    /**
     * Mongo DB
     *
     * @param db_schema
     * @param schema_name
     * @returns {*}
     */
    connect_mongo: function (db_schema, schema_name) {
        // connect to database
        mongoose.connect('mongodb://' + process.env.MONGO_HOST + ':' + process.env.MONGO_PORT + '/storage/database');
        var Schema = mongoose.Schema;

        // create a schema for item
        var DBSchema = new Schema(db_schema);
        DBSchema.plugin(mongoosePaginate);

        // we need to create a model using it
        return mongoose.model(schema_name, DBSchema);
    },

    /**
     * Pagination mongo
     *
     * @param options
     * @returns {{page: number, limit: Number}}
     */
    pagination_mongo: function (options) {
        return {
            page: (options.page > 0 && !isNaN(options.page)) ? parseInt(options.page) : 1,
            limit: (options.limit > 0 && !isNaN(options.page)) ? parseInt(options.limit) : parseInt(process.env.PAGINATION_LIMIT)
        };
    },

    /**
     * Pagination sql
     *
     * @param options
     * @returns {{page: number, resultsPerPage: Number}}
     */
    pagination_sql: function (options) {
        return {
            page: (options.page > 0 && !isNaN(options.page)) ? parseInt(options.page) : 1,
            resultsPerPage: (options.limit > 0 && !isNaN(options.page)) ? parseInt(options.limit) : parseInt(process.env.PAGINATION_LIMIT)
        };
    },

    /**
     * Success response
     *
     * @param result
     */
    success_response: function (result) {
        if (result.hasOwnProperty("docs")) {
            if (result.docs instanceof Array) {
                return {
                    success: true,
                    data: result.docs,
                    pagination: {
                        total: result.total,
                        limit: result.limit,
                        page: result.page,
                        pages: result.pages
                    }
                };
            }
        }

        return {
            success: true,
            data: result
        };
    },

    /**
     * Failed response
     *
     * @param errors
     * @returns {{success: boolean, errors: *[]}}
     */
    failed_response: function (errors) {
        console.log(errors);

        return {
            success: false,
            errors: (typeof errors === 'string') ? [errors] : errors
        };
    },

    /**
     * Response
     *
     * @param error
     * @param results
     * @returns {*}
     */
    response: function (error, results) {
        if (error) {
            return this.failed_response(error);
        }

        if (!results) {
            return this.failed_response('No query results found.');
        }

        return this.success_response(results);
    }
};