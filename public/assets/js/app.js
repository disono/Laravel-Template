/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 *
 * Angular configurations
 */

// angular
var WBApp = angular.module('WBApp', [], function ($interpolateProvider) {
    $interpolateProvider.startSymbol('<%');
    $interpolateProvider.endSymbol('%>');
});

// angular configs
WBApp.config(['$httpProvider', function ($httpProvider) {
    $httpProvider.defaults.headers.common = {
        'X-CSRF-Token': jQ('meta[name="_token"]').attr('content'),
        "Content-Type": "application/json; charset=utf-8",
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    };
}]);