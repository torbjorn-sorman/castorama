angular.module('castorama.controllers', [])

.controller('MainController', function ($scope, $location, User) {    
    $scope.navigate = function (path, ind) {
        User.status.nav = ind;
        $location.path(path);
    };
    $scope.navClass = function (ind) {
        return ind == $scope.status.nav ? "button-selected" : "button-navigation";
    }
    $scope.logout = function () {
        User.logout(function (data) { if (data) $location.path('home'); });
    };
    $scope.status = User.status;
    User.refresh();
})

.controller('HomeController', function ($scope, ScoreTable, User) {
    User.status.nav = 0;
    $scope.castorama = new Castorama(ScoreTable);
    $scope.genderSelect = function (gen) {
        $scope.castorama.gender.toggle = (gen == 'men');
    }
})

.controller('StatsController', function ($scope) {
    console.log("StatsController");
})

.controller('ParserController', function ($scope, $http) {
    $scope.loading = false;
    $scope.options = { year: 2001 };
    $scope.updateDB = function (options) {
        $scope.loading = true;
        $http.get('/index.php/parser/update/' + options.year).success(function (data) {
            console.log(data);
            $scope.loading = false;
        });
    }
    $scope.timeout = function () {        
        $scope.loading = false;
    }
})

.controller('AdminController', function ($scope, $location, User) {
    console.log("AdminController");
    $scope.login = { username: 'tb', password: 'cooling' };
    $scope.verifyLogin = function (login) {
        User.login(login, function (data) { if (data) $location.path('home'); });
    }
});

