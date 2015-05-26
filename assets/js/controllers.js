angular.module('castorama.controllers', [])

.controller('MainController', function ($scope, $location, User) {
    $scope.navigate = function (path) {
        $location.path(path);
    };
    $scope.logout = function () {
        User.logout(function (data) { if (data) $location.path('home'); });
    };
    $scope.status = User.status;
    User.refresh();
})

.controller('HomeController', function ($scope, ScoreTable) {
    $scope.castorama = new Castorama(ScoreTable);
    $scope.genderSelect = function (gen) {
        $scope.castorama.gender.toggle = (gen == 'men');
    }
})

.controller('StatsController', function ($scope) {
    console.log("StatsController");
})

.controller('ParserController', function ($scope) {
    $scope.options = { year: 2001 };
    $scope.updateDB = function (options) {
        console.log(options);
    }
})

.controller('AdminController', function ($scope, $location, User) {
    console.log("AdminController");
    $scope.login = { username: 'tb', password: 'cooling' };
    $scope.verifyLogin = function (login) {
        User.login(login, function (data) { if (data) $location.path('home'); });
    }
});