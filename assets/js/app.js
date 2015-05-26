////////////////////
// app.js
// Torbjörn Sörman
// 2015-04-20
////////////////////

var app = angular.module('castorama', ['ngRoute', 'castorama.services', 'castorama.controllers', 'castorama.filters']);

// App Configurations
app.config(function ($routeProvider, $locationProvider) {
    $routeProvider
    .when('/', {
        templateUrl: '/home',
        controller: 'HomeController'
    })
    .when('/home', {
        templateUrl: '/home',
        controller: 'HomeController'
    })
    .when('/stats', {
        templateUrl: '/stats',
        controller: 'StatsController'
    })
    .when('/parser', {
        templateUrl: '/parser',
        controller: 'ParserController'
    })
    .when('/admin', {
        templateUrl: '/admin',
        controller: 'AdminController'
    })
    .when('/logout', {
        templateUrl: '/logout',
        controller: 'AdminController'
    });
});