angular.module('castorama.services', [])

.factory('User', function ($http) {
    var userStatus = new UserStatus();    
    return {
        login: function(login, callback) {
            $http.post('/index.php/login/login/', login).success(function (data, status, headers, config) {
                if (data) {
                    userStatus.loggedin = true;
                    userStatus.username = login.username;
                }
                callback(data);
            });
        },
        logout: function(callback) {
            $http.get('/index.php/login/logout/').success(function (data, status, headers, config) {
                if (data)
                    userStatus.loggedin = false;
                callback(data);
            });
        },
        refresh: function() {
            $http.get('/index.php/login/isloggedin/').success(function (data, status, headers, config) {
                if (data) {
                    userStatus.loggedin = true;
                    userStatus.username = data;
                } else {
                    userStatus.loggedin = false;
                    userStatus.username = "";
                }
            });
        },
        status: userStatus
    };
})

.factory('RecordTracker', function () {
    var records = [];
    var fetchRecord = function ($http, id, callback) {
        $http.get('stats/get/' + id).success(function (data) {
            records[id] = data[0];
            callback(data[0]);
            console.log(data[0]);
        });
    }
    return {
        set: function (record) {
            records[record.id] = record;
        },
        get: function (id) {
            if (!(id in records)) {
                fetchRecord(arguments[1], id, arguments[2]);
                return null;
            }
            return records[id];
        }
    };
})

.factory('ScoreTable', function ($http) {
    var source = 'assets/js/data/score_table/';
    var score_table = {
        men: {
            discus: null,
            hammer: null,
            javelin: null,
            shot: null
        },
        women: {
            discus: null,
            hammer: null,
            javelin: null,
            shot: null
        }
    };
    // Men
    $http.get(source + 'men/shot.json').success(function (data, status, headers, config) {
        score_table.men.shot = data;
    });
    $http.get(source + 'men/javelin.json').success(function (data) {
        score_table.men.javelin = data;
    });
    $http.get(source + 'men/discus.json').success(function (data) {
        score_table.men.discus = data;
    });
    $http.get(source + 'men/hammer.json').success(function (data) {
        score_table.men.hammer = data;
    });
    // Women
    $http.get(source + 'women/shot.json').success(function (data) {
        score_table.women.shot = data;
    });
    $http.get(source + 'women/javelin.json').success(function (data) {
        score_table.women.javelin = data;
    });
    $http.get(source + 'women/discus.json').success(function (data) {
        score_table.women.discus = data;
    });
    $http.get(source + 'women/hammer.json').success(function (data) {
        score_table.women.hammer = data;
    });

    return {
        lookup: function (gender, event, result) {
            if (score_table[gender][event] == null)
                return 0;
            var length = score_table[gender][event].length;

            for (var i = 0; i < length; ++i) {
                // Iterate from highest to lowest, equal or higher is the correct score.
                // Tested to increase performance by guessing an initial start position, iteration was shorter but data set to small and siginificant overhead caused slower performance;
                var row = score_table[gender][event][i];
                if (result >= row.result) {
                    return row.score;
                }
            }
            return 0;
        }
    };
});

function UserStatus() {    
    var loggedin = false;
    var username = "";
    var nav = 0;
    this.__defineGetter__("loggedin", function () {
        return loggedin;
    });
    this.__defineSetter__("loggedin", function (val) {
        loggedin = val;
    });
    this.__defineGetter__("username", function () {
        return loggedin ? username : "";
    });
    this.__defineSetter__("username", function (val) {
        username = val;
    });
    this.__defineGetter__("nav", function () {
        return nav;
    });
    this.__defineSetter__("nav", function (val) {
        nav = val;
    });
}