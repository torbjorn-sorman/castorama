
angular.module('castorama.controllers', [])

.controller('MainController', function ($scope, $location, User)
{
    $scope.navigate = function (path, ind) {
        User.status.nav = ind;
        $location.path(path);
    };
    $scope.logout = function () {
        User.logout(function (data) { if (data) $location.path('home'); });
    };
    $scope.status = User.status;
    User.refresh();
})

.controller('HomeController', function ($scope, ScoreTable, User)
{
    User.status.nav = 0;
    $scope.castorama = new Castorama(ScoreTable);
    $scope.genderSelect = function (gen) {
        $scope.castorama.gender.toggle = (gen == 'men');
    }
})

.controller('StatsController', function ($scope, $http, $filter, $timeout)
{
    var refreshQueued = false;
    var loading = false;
    var canLoadMore = true;
    var offset = 0;
    var limit = 25;
    var options = {
        gen: 'all',
        from: new Date(1980, 0, 1),
        to: new Date(),
        dir: 'desc',
        order: 'date'
    };
    var search = {
        name: "",
        club: "",
        location: ""
    };
    var orderingItems = [
      new OrderItem("Datum", 'date'),
      new OrderItem("Namn", 'name'),
      new OrderItem("Plats", 'location'),
      new OrderItem("Förening", 'club'),
      new OrderItem("Poäng", 'score'),
      new OrderItem("Kula", 'shot', 'shot'),
      new OrderItem("Spjut", 'javelin', 'javelin'),
      new OrderItem("Diskus", 'discus', 'discus'),
      new OrderItem("Slägga", 'hammer', 'hammer')
    ];
    
    $scope.showSearch = false;
    $scope.showOptions = false;

    $scope.orderItems = orderingItems;
    $scope.opt = options;
    $scope.search = search;
    $scope.result = [];
    $scope.noMoreContent = function () {
        return !canLoadMore;
    };
    $scope.showLoading = function () {
        return loading;
    }
    $scope.refresh = function (reset) {
        if (loading) {
            if (!refreshQueued) {
                refreshQueued = true;
                $timeout($scope.refresh, 1000, true, reset);
            }
        } else {
            refreshQueued = false;
            if (reset) {
                $scope.result = [];
                offset = 0;
                canLoadMore = true;
            }
            console.log(postData(limit, offset));
            loading = true;
            $http.post('/stats/search/', postData(limit, offset)).success(function (data) {                
                for (var i = 0; i < data.length; ++i)
                    $scope.result.push(data[i]);
                offset += data.length;
                loading = false;
                canLoadMore = (data.length == limit);
            });
        }
    }
    $scope.$watch('opt', function () {
        $scope.refresh(true);
    }, true);
    $scope.$watch('search', function () {        
        $scope.refresh(true);
    }, true);
    $scope.clearSearch = function () {
        $scope.search = {
            name: "",
            club: "",
            location: ""
        };
    }
    $scope.toMeter = function (val) {
        if (val == 0)
            return "";
        var text = val, fixed;
        return text.replace(/(^\d?\d)(\d\d)/, "$1,$2");
    }
    $scope.nonEventSelected = function() {
        var p = $scope.opt.order;
        return p == 'name' || p == 'date' || p == 'location' || p == 'club';
    }
    function postData(limit, offset) {
        var d = {
            fromdate: $filter('date')($scope.opt.from, 'yyyy-MM-dd'),
            todate: $filter('date')($scope.opt.to, 'yyyy-MM-dd'),
            gender: $scope.opt.gen,
            name: $scope.search.name,
            club: $scope.search.club,
            location: $scope.search.location,
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

function OrderItem(title, column) {
    if (arguments.length > 2)
        return { title: title, column: column, name: arguments[2], isImg: true };
    return { title: title, column: column, isImg: false };
}