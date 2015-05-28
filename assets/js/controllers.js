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

.controller('StatsController', function ($scope, $http) {
    var canLoadMore = true;
    var offset = 0;
    var limit = 20;
    var options = {
        gen: 'all',
        from: 1981,
        to: 2015,
        dir: 'desc',
        order: 'date'
    };
    var search = {
        name: "",
        club: ""
    };

    $scope.loading = false;
    $scope.showSearch = false;
    $scope.showOptions = false;
    $scope.opt = options;
    $scope.search = search;
    $scope.result = [];
    $scope.addMoreItems = function () {        
        $scope.loading = true;
        $http.post('/stats/search/', postData(limit, offset)).success(function (data) {
            $scope.loading = false;
            canLoadMore = (data.length == limit);
            for (var i = 0; i < data.length; ++i) {
                $scope.result.push(data[i]);
            }
            offset += data.length;
        });
    };
    $scope.moreItemsCanBeLoaded = function () {
        return canLoadMore;
    };
    $scope.toggleSearch = function () {
        $scope.showSearch = !$scope.showSearch;
    };
    $scope.toggleOptions = function () {
        $scope.showOptions = !$scope.showOptions;
    };
    $scope.clearSearch = function () {
        console.log('clear search-fields');
    }
    function postData(limit, offset) {
        var d = {
            fromdate: $scope.opt.from + "-01-01",
            todate: ($scope.opt.to + 1) + "-01-01",
            gender: $scope.opt.gen,
            name: $scope.search.name,
            club: $scope.search.club,
            limit: limit,
            offset: offset,
            orderby: $scope.opt.order,
            orderbydir: $scope.opt.dir
        };
        return d;
    };
})

.controller('SeasonController', function ($scope, $http) {
    $scope.showMen = false;
    $scope.showWomen = false;
    $scope.showClub = false;
    $scope.result = { men: [], women: [], club: [] };
    $scope.showAll = false;
    $http.get('/stats/season/2014/1/0').success(function (data) {
        for (var i = 0; i < data.length; ++i) {
            $scope.result.men.push(data[i]);
        }
    });
    $http.get('/stats/season/2014/0/0').success(function (data) {
        for (var i = 0; i < data.length; ++i) {
            $scope.result.women.push(data[i]);
        }
    });
    $http.get('/stats/season/2014/2/0').success(function (data) {
        for (var i = 0; i < data.length; ++i) {
            $scope.result.club.push(data[i]);
        }
    });
    $scope.toggleMen = function () {
        $scope.showMen = !$scope.showMen;
    };
    $scope.toggleWomen = function () {
        $scope.showWomen = !$scope.showWomen;
    };
    $scope.toggleClub= function () {
        $scope.showClub = !$scope.showClub;
    };
})

.controller('ParserController', function ($scope, $http) {
    $scope.loading = false;
    $scope.year = 2001;
    $scope.season = 2014;
    $scope.response = {
        update: { message: false, text: "" },
        season: { message: false, text: "" },
        score: { message: false, text: "" }
    };
    $scope.updateDB = function (year) {
        if ($scope.loading)
            return;
        $scope.loading = true;
        $http.get('/index.php/parser/update/' + year).success(function (data) {
            $scope.response.update.message = true;
            $scope.response.update.text = JSON.stringify(data);
            $scope.loading = false;
        });
    }
    $scope.updateSeason = function (season) {
        if ($scope.loading)
            return;
        $scope.loading = true;
        $http.get('/index.php/parser/season/' + season).success(function (data) {
            $scope.response.season.message = true;
            $scope.response.season.text = JSON.stringify(data);
            $scope.loading = false;
        });
    }
    $scope.updateScore = function () {
        if ($scope.loading)
            return;
        $scope.loading = true;
        $http.get('/index.php/parser/score/').success(function (data) {
            $scope.response.score.message = true;
            $scope.response.score.text = JSON.stringify(data);
            $scope.loading = false;
        });
    }
})

.controller('AdminController', function ($scope, $location, User) {
    console.log("AdminController");
    $scope.login = { username: 'tb', password: 'cooling' };
    $scope.verifyLogin = function (login) {
        User.login(login, function (data) { if (data) $location.path('home'); });
    }
});

