////////////////////
// app.js
// Torbjörn Sörman
// 2015-04-20
////////////////////

var app = angular.module('LIFnewsLive', ['ngRoute']);

// Main Controller
app.controller('MainController', function ($scope, $route, $routeParams, $location) {
    $scope.$route = $route;
    $scope.$location = $location;
    $scope.$routeParams = $routeParams;
});

// Home Controller
app.controller('HomeController', function ($scope, $routeParams) {
    $scope.click = function (text) {
        console.log(text);
    }
});

// Teams Controller
app.controller('TeamsController', function ($scope, $http, $routeParams) {
    $scope.teams = new Array();
    $http.get('teams/getallteams').success(function (data) {
        $scope.teams = data;
    });
    $scope.addTeam = function (teamName) {
        var post_team = {
            data: {
                name: teamName
            }
        };
        $http.post('/teams/insert/', post_team).success(function (data, status, headers, config) {
            $scope.teams.push(data);
            $scope.teamName = "";
        });
    }
    $scope.removeTeam = function (teamID) {
        $http.get('/teams/remove/' + teamID).success(function (data, status, headers, config) {
            if (data == 1) {
                $scope.teams = $scope.teams.filter(function (el) {
                    return el.id !== teamID;
                });
            } else {
                console.log("Failed to remove element with ID: " + teamID);
            }
        });
    }
});

// Games Controller
app.controller('GamesController', function ($scope, $http, $routeParams) {
    $scope.new_game = { start_time: myDatetime() };

    $scope.games = new Array();
    $scope.teams = new Array();
    importTeamsAndGames($scope, $http, function (data) { $scope.teams = data }, function (data) { $scope.games = data });

    $scope.addGame = function (game) {
        var post_game = {
            data:
            {
                start_time: game.start_time,
                home_team: game.home_team.id,
                away_team: game.away_team.id
            }
        };
        $http.post('/games/insert/', post_game).success(function (data, status, headers, config) {
            $scope.games.push(data);
        });
    }
    $scope.removeTeam = function (teamID) {
        $http.get('/games/remove/' + teamID).success(function (data, status, headers, config) {
            if (data == 1) {
                $scope.games = $scope.games.filter(function (el) {
                    return el.id !== teamID;
                });
            } else {
                console.log("Failed to remove element with ID: " + teamID);
            }
        });
    }
    $scope.teamName = function (teamID) {
        return teamName($scope.teams, teamID);
    }
    $scope.localTime = function (time) {
        var date = new Date(time + " UTC");
        //return date.getFullYear() + "-" + date.getMonth() + "-" + date.getDate() + " " + date.getHours() + ":" + date.getMinutes();
        return moment(date).format('YYYY-MM-DD HH:mm');
    }
});

// Report Controller
app.controller('ReportController', function ($scope, $http) {
    $scope.messages = new Array();
    $scope.message = {
        seconds: new Quantity(0),
        minutes: new Quantity(0)
    }
    // get all types and inputs from server...
    $scope.reportType = new Array("Comment", "Goal", "Penalty");
    $scope.selectedType = "Comment";

    $scope.games = new Array();
    $scope.teams = new Array();
    importTeamsAndGames($scope, $http, function (data) { $scope.teams = data }, function (data) { $scope.games = data });

    $scope.gameDisplay = function (game) {
        return game.start_time + " " + teamName($scope.teams, game.home_team) + " vs " + teamName($scope.teams, game.away_team);
    }
    $scope.sendMessage = function (message) {
        message.date = new Date();
        $http.post('report/post', message).success(function (data) {
            if (data) {
                $scope.messages.push(message);
            }
        });
        $scope.reset();
    }
    $scope.reset = function () {
        $scope.message = {
            game: {},
            home_score: 0,
            away_score: 0,
            text: "",
            client: "lifnews",
            seconds: new Quantity(0, 59, 0),
            minutes: new Quantity(0, 20, 0)
        };
    }
    $scope.reset();
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
    });
});

app.filter('excludeFrom', [function () {
    return function (array, expression, comparator) {
        return array.filter(function (item) {
            return !expression || !angular.equals(item, expression);
        });
    };
}]);

app.filter('range', function () {
    return function (input, total) {
        total = parseInt(total);
        for (var i = 0; i < total; i++)
            input.push(i);
        return input;
    };
});

function importTeamsAndGames($scope, $http, teamsCallback, gamesCallback) {
    $http.get('teams/getallteams').success(function (data) {
        teamsCallback(data);
        $http.get('games/getallgames').success(function (data) {
            if (data && data != 'null') {
                gamesCallback(data);
            }
        });
    });
}

function teamName(teams, teamID) {
    for (i = 0; i < teams.length; ++i) {
        var team = teams[i];
        if (teamID == team['id'])
            return team['name'];
    }
}

function myDatetime() {
    var now = new Date();
    return new Date(now.getFullYear(), now.getMonth(), now.getDate(), 19);
}

function Quantity(min, max, val) {
    var min = min;
    var max = max;
    var value = val;
    var range = val;

    this.__defineGetter__("value", function () {
        return value;
    });

    this.__defineSetter__("value", function (val) {
        val = parseInt(val);
        if (val >= min && val <= max) {
            value = val;
            range = value;
        }
    });

    this.__defineGetter__("range", function () {
        return range;
    });

    this.__defineSetter__("range", function (val) {
        val = parseInt(val);
        if (val >= min && val <= max) {
            range = parseInt(val);
            value = range;
        }
    });
}