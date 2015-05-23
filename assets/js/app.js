////////////////////
// app.js
// Torbjörn Sörman
// 2015-04-20
////////////////////

var app = angular.module('castorama', ['ngRoute']);

// Main Controller
app.controller('MainController', function ($scope, $route, $routeParams, $location) {
    $scope.$route = $route;
    $scope.$location = $location;
    $scope.$routeParams = $routeParams;
    $scope.navigate = function (path) {
        $location.path(path);
    };
});

// Home Controller
app.controller('HomeController', function ($scope, $routeParams) {
    console.log("HomeController");
});

// Games Controller
app.controller('StatsController', function ($scope, $http, $routeParams) {
    console.log("StatsController");
});

// Report Controller
app.controller('AdminController', function ($scope, $http) {
    console.log("AdminController");
});

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
    .when('/admin', {
        templateUrl: '/admin',
        controller: 'AdminController'
    });
});