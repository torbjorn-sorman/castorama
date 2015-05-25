angular.module('castorama.controllers', [])

// Main Controller
.controller('MainController', function ($scope, $route, $routeParams, $location, User) {
    $scope.$route = $route;
    $scope.$location = $location;
    $scope.$routeParams = $routeParams;
    $scope.navigate = function (path) {
        $location.path(path);
    };
    $scope.status = User.status;
    $scope.logout = function () {
        User.logout(function (data) { if (data) $location.path('home'); });
    };
    User.refresh();
})

// Home Controller
.controller('HomeController', function ($scope, $routeParams) {
    console.log("HomeController");
})

// Games Controller
.controller('StatsController', function ($scope, $http, $routeParams) {
    console.log("StatsController");
})

// Report Controller
.controller('AdminController', function ($scope, $location, $route, User) {
    console.log("AdminController");
    $scope.login = { username: 'tb', password: 'cooling' };
    $scope.verifyLogin = function (login) {
        User.login(login, function (data) { if (data) $location.path('home'); });
    }
});