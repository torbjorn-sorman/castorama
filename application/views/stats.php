<div class="drop-down">
    <div class="drop-down-head" ng-click="showOptions = !showOptions">
        <div class="drop-down-title">Alternativ</div>
        <div class="drop-down-toggle {{showOptions ? 'ion-chevron-up' : 'ion-chevron-down'}}"></div>
    </div>
    <div ng-if="showOptions">
        <div class="stats-alt">
            <p>
                <span>Klass: </span>
                <button ng-click="opt.gen = 'all'" class="stats-alt-button {{ opt.gen == 'all' ? 'selected' : '' }}">
                    <span class="ion-man"></span>&nbsp;<span class="ion-woman"></span>
                </button>
                <button ng-click="opt.gen = 'men'" class="stats-alt-button {{ opt.gen == 'men' ? 'selected' : '' }}">
                    <span class="ion-man"></span>
                </button>
                <button ng-click="opt.gen = 'women'" class="stats-alt-button {{ opt.gen == 'women' ? 'selected' : '' }}">
                    <span class="ion-woman"></span>
                </button>
            </p>
            <p>
                <span>Fr&aring;n:</span><input type="date" value="1980-01-01" ng-model="opt.from" />
                <span>Till:</span><input type="date" value="2015-12-31" ng-model="opt.to" />
            </p>
        </div>
        <div class="stats-alt">
            <p>
                <span>Ordning: </span>
                <button ng-click="opt.dir = 'asc'" class="stats-alt-button {{ opt.dir == 'asc' ? 'selected' : '' }}">
                    <span class="ion-arrow-up-c"></span>
                </button>
                <button ng-click="opt.dir = 'desc'" class="stats-alt-button {{ opt.dir == 'desc' ? 'selected' : '' }}">
                    <span class="ion-arrow-down-c"></span>
                </button>
            </p>
            <p>
                <span>Sortera:</span>
                <button ng-repeat="p in orderItems" ng-if="$index < 4" class="stats-alt-button {{ opt.order == p.column ? 'selected' : '' }}" ng-click="opt.order = p.column">
                    <span ng-if="!p.isImg">{{p.title}}</span>
                    <img ng-if="p.isImg" class="icon-small image-clickable" ng-src="{{'assets/img/events/' + p.name + (opt.order == p.column ? '-inverted' : '') + '.png'}}" src="//:0" />
                </button>
                <br />
            </p>
            <p>
                <button ng-repeat="p in orderItems" ng-if="$index >= 4" class="stats-alt-button {{ opt.order == p.column ? 'selected' : '' }}" ng-click="opt.order = p.column">
                    <span ng-if="!p.isImg">{{p.title}}</span>
                    <img ng-if="p.isImg" class="icon-small image-clickable" ng-src="{{'assets/img/events/' + p.name + (opt.order == p.column ? '-inverted' : '') + '.png'}}" src="//:0" />
                </button>
            </p>
        </div>

    </div>
</div>
<div class="drop-down">
    <div class="drop-down-head" ng-click="showSearch = !showSearch">
        <div class="drop-down-title">S&ouml;k</div>
        <div class="drop-down-toggle {{showSearch ? 'ion-chevron-up' : 'ion-chevron-down'}}"></div>
    </div>
    <div ng-if="showSearch" class="stats-alt">
        <input type="text" placeholder="Namn" ng-model="search.name" />
        <input type="text" placeholder="Klubb" ng-model="search.club" />
        <input type="text" placeholder="Plats" ng-model="search.location" />
        <button class="stats-button ion-close-circled" ng-click="clearSearch()">&nbsp;Rensa</button>
    </div>
</div>
<div class="drop-down stats-result">
    <div class="drop-down-head">
        <div class="drop-down-title">Resultat</div>
    </div>
    <div infinite-scroll="refresh(false)" infinite-scroll-distance="1" infinite-scroll-disabled="noMoreContent()">
        <table class="stats-table">
            <thead>
                <tr>
                    <th class="{{nonEventSelected() ? 'col-selected' : ''}}">Information</th>
                    <th ng-repeat="p in ['score', 'shot', 'javelin', 'discus', 'hammer']" class="{{ opt.order == p ? 'col-selected' : ''}}">
                        <span ng-if="p == 'score'">Po&auml;ng</span>
                        <img ng-if="p != 'score'" class='icon-small' src="assets/img/events/{{p}}.png" />
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="r in result">
                    <td class="{{nonEventSelected() ? 'col-selected' : ''}}">
                        <p class="stats-date-location">{{r.date}}, {{r.location}}</p>
                        <p class="stats-name-club">{{r.name + (r.birthyear != 0 ? ' -' + r.birthyear : '')}}, {{r.club}}</p>
                    </td>
                    <td ng-repeat="p in ['score', 'shot', 'javelin', 'discus', 'hammer']" class="{{ opt.order == p ? 'col-selected' : ''}}">
                        {{p=='score' ? r.score : toMeter(r[p])}}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div ng-if="showLoading()">
        <div class="stats-load animate-loading ion-load-d"></div>
        <div class="stats-load"></div>
    </div>
</div>

