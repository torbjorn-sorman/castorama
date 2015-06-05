<div class="drop-down">
    <div class="drop-down-head" ng-click="toggleOptions()">
        <div class="drop-down-title">Alternativ</div>
        <div class="drop-down-toggle {{showOptions ? 'ion-chevron-up' : 'ion-chevron-down'}}"></div>
    </div>
    <div ng-if="showOptions">
        <div class="stats-alt">
        <span>Klass: ({{opt.gen == 'all' ? 'alla' : (opt.gen == 'men' ? 'm&auml;n' : 'kvinnor')}})</span><br />
            <button ng-click="opt.gen = 'all'" class="stats-alt-button {{ opt.gen == 'all' ? 'selected' : '' }}">
                <span class="ion-man"></span>&nbsp;<span class="ion-woman"></span>
            </button>
            <button ng-click="opt.gen = 'men'" class="stats-alt-button {{ opt.gen == 'men' ? 'selected' : '' }}">
                <span class="ion-man"></span>
            </button>
            <button ng-click="opt.gen = 'women'" class="stats-alt-button {{ opt.gen == 'women' ? 'selected' : '' }}">
                <span class="ion-woman"></span>
            </button><br />
            <span>Fr&aring;n:</span><input type="date" value="1980-01-01" ng-model="opt.from" />
            <span>Till:</span><input type="date" value="2015-12-31" ng-model="opt.to" />
        </div>
        <div class="stats-alt">
            <p>
                <span>Ordning ({{opt.dir == 'asc' ? 'stigande' : 'fallande'}}):</span><br />
                <button ng-click="opt.dir = 'asc'" class="stats-alt-button {{ opt.dir == 'asc' ? 'selected' : '' }}">
                    <span class="ion-arrow-up-c"></span>
                </button>
                <button ng-click="opt.dir = 'desc'" class="stats-alt-button {{ opt.dir == 'desc' ? 'selected' : '' }}">
                    <span class="ion-arrow-down-c"></span>
                </button>
            </p>
            <p>
                <span>Sortering:</span><br />
                <button ng-repeat="p in orderItems" ng-if="$index < 5" class="stats-alt-button {{ opt.order == p.column ? 'selected' : '' }}" ng-click="opt.order = p.column">
                    <span ng-if="!p.isImg">{{p.title}}</span>
                    <img ng-if="p.isImg" class="icon-small image-clickable" ng-src="{{'assets/img/events/' + p.name + (opt.order == p.column ? '-inverted' : '') + '.png'}}" src="//:0" />
                </button>
                <br />
                <button ng-repeat="p in orderItems" ng-if="$index >= 5" class="stats-alt-button {{ opt.order == p.column ? 'selected' : '' }}" ng-click="opt.order = p.column">
                    <span ng-if="!p.isImg">{{p.title}}</span>
                    <img ng-if="p.isImg" class="icon-small image-clickable" ng-src="{{'assets/img/events/' + p.name + (opt.order == p.column ? '-inverted' : '') + '.png'}}" src="//:0" />
                </button>
            </p>
        </div>

    </div>
</div>
<div class="drop-down">
    <div class="drop-down-head" ng-click="toggleSearch()">
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

    <div infinite-scroll="addMoreItems()" infinite-scroll-distance="1" infinite-scroll-disabled="loading">
        <table class="stats-table">
            <tr class="row-header">
                <th>Datum</th>
                <th>Plats</th>
                <th>Namn</th>
                <th>F&ouml;rening</th>
                <th class="stats-header-number">Po&auml;ng</th>
                <th class="stats-header-number">
                    <img class="icon-small" src="assets/img/events/shot.png" /></th>
                <th class="stats-header-number">
                    <img class="icon-small" src="assets/img/events/javelin.png" /></th>
                <th class="stats-header-number">
                    <img class="icon-small" src="assets/img/events/discus.png" /></th>
                <th class="stats-header-number">
                    <img class="icon-small" src="assets/img/events/hammer.png" /></th>
            </tr>
            <tr class="row-item" ng-repeat="r in result">
                <td>{{r.date}}</td>
                <td>{{r.location}}</td>
                <td>{{r.name}}</td>
                <td>{{r.club}}</td>
                <td class="stats-number">{{r.score}}</td>
                <td class="stats-number">{{toMeter(r.shot)}}</td>
                <td class="stats-number">{{toMeter(r.javelin)}}</td>
                <td class="stats-number">{{toMeter(r.discus)}}</td>
                <td class="stats-number">{{toMeter(r.hammer)}}</td>
            </tr>
        </table>
    </div>
    <div ng-if="moreItemsCanBeLoaded()">
        <div class="stats-load animate-loading ion-load-d"></div>
        <div class="stats-load"></div>
    </div>
</div>

