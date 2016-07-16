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