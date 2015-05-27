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
        templateUrl: '/index.php/home',
        controller: 'HomeController'
    })
    .when('/home', {
        templateUrl: '/index.php/home',
        controller: 'HomeController'
    })
    .when('/stats', {
        templateUrl: '/index.php/stats',
        controller: 'StatsController'
    })
    .when('/season', {
        templateUrl: '/index.php/season',
        controller: 'SeasonController'
    })
    .when('/parser', {
        templateUrl: '/index.php/parser',
        controller: 'ParserController'
    })
    .when('/admin', {
        templateUrl: '/index.php/login',
        controller: 'AdminController'
    })
    .when('/logout', {
        templateUrl: '/index.php/logout',
        controller: 'AdminController'
    });
});