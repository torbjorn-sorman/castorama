
function Castorama(scoreTable) {
    var table = scoreTable;
    var gender = new Gender('men');
    var sum = new Sum();
    var events = {
        shot: new Event(table, gender, sum, 'shot', ''),
        javelin: new Event(table, gender, sum, 'javelin', ''),
        discus: new Event(table, gender, sum, 'discus', ''),
        hammer: new Event(table, gender, sum, 'hammer', '')
    };
    gender.sum = sum;
    sum.events = events;

    this.__defineGetter__("events", function () {
        return events;
    });
    this.__defineGetter__("sum", function () {
        return sum;
    });
    this.__defineGetter__("gender", function () {
        return gender;
    });
    this.setFromRecord = function (record) {
        gender = new Gender(record.sex == 1 ? 'men' : 'women');
        events.shot.result = record.shot;
        events.discus.result = record.discus;
        events.javelin.result = record.javelin;
        events.hammer.result = record.hammer;
    }
}

function Sum() {
    var events = null;

    this.__defineGetter__("value", function () {
        var total = 0;
        for (e in events) {
          var val = parseInt(events[e].score);
          total += isNaN(val) ? 0 : val;
        }
        return isNaN(total) || total < 1 ? 0 : total;
    });

    this.__defineGetter__("events", function () {
        return events;
    });
    this.__defineSetter__("events", function (val) {
        events = val;
    });
}

function Gender(g) {
    var value = g;
    var menSelected = (g == 'men');
    // Sum is needed to trigger recalculation
    var sum = null;

    this.__defineGetter__("toggle", function () {
        return menSelected;
    });
    this.__defineSetter__("toggle", function (val) {
        menSelected = val;
        value = val ? 'men' :'women';
        sum.value;
    });

    this.__defineGetter__("value", function () {
        return value;
    });

    this.disp = function (g) {
        if (g == 'men')
            return menSelected ? "M\u00E4n" : 'M\u00E4n';
        return menSelected ? "Kvinnor" : 'Kvinnor';
    }

    this.__defineSetter__("sum", function (val) {
        sum = val;
    });
}

function Event(t, g, s, e, n) {
    var result = "";
    var score = 0;

    var table = t;
    var event = e;
    var name = n;
    if (n == '')
        name = 'assets/img/events/' + e + '.png';
    var gender = g;
    var sum = s;

    this.__defineGetter__("result", function () {
        return result;
    });

    this.__defineSetter__("result", function (val) {        
        val = parseFloat(val);
        result = val;
        sum.value;        
    });

    this.__defineGetter__("score", function () {
        score = table.lookup(gender.value, event, result);
        return score < 1 ? 0 : score;
    });

    this.__defineGetter__("name", function () {
        return name;
    });
}