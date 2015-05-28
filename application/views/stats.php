<div class="drop-down">
    <div class="drop-down-head" ng-click="toggleOptions()">
        <div class="drop-down-title">Alternativ</div>
        <div class="drop-down-toggle {{showOptions ? 'ion-chevron-up' : 'ion-chevron-down'}}"></div>
    </div>
    <div ng-if="showOptions">
        <form>
            <div class="stats-alt">
                <div class="stats-alt-gen ion-man">
                    <input type="radio" name="gender" value="men" />
                </div>
                <div class="stats-alt-gen ion-woman">
                    <input type="radio" name="gender" value="women" />
                </div>
                <div class="stats-alt-gen ion-man"></div>
                <div class="stats-alt-gen ion-woman">
                    <input type="radio" name="gender" value="all" checked />
                </div>
            </div>
            <div class="stats-alt">
                <p><span>Fr&aring;n:</span><input type="date" value="1980-01-01" /></p>
                <p><span>Till:</span><input type="date" value="2015-12-31" /></p>
            </div>
        </form>

    </div>
</div>
<div class="drop-down">
    <div class="drop-down-head" ng-click="toggleSearch()">
        <div class="drop-down-title">S&ouml;k</div>
        <div class="drop-down-toggle {{showOptions ? 'ion-chevron-up' : 'ion-chevron-down'}}"></div>
    </div>
    <div ng-if="showSearch" class="stats-alt">
        <input type="text" placeholder="Namn" />
        <input type="text" placeholder="Klubb" />
        <input type="text" placeholder="Plats" />
        <button class="stats-clear" ng-click="clearSearch()">Rensa</button>
    </div>
</div>
<div class="drop-down">
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
                <th>Po&auml;ng</th>
                <th><img class="icon-small" src="assets/img/events/shot.png" /></th>
                <th><img class="icon-small" src="assets/img/events/javelin.png" /></th>
                <th><img class="icon-small" src="assets/img/events/discus.png" /></th>
                <th><img class="icon-small" src="assets/img/events/hammer.png" /></th>
            </tr>
            <tr class="row-item" ng-repeat="r in result">
                <td>{{r.date}}</td>
                <td>{{r.location}}</td>
                <td>{{r.name}}</td>
                <td>{{r.club}}</td>
                <td>{{r.score}}</td>
                <td>{{r.shot}}</td>
                <td>{{r.javelin}}</td>
                <td>{{r.discus}}</td>
                <td>{{r.hammer}}</td>
            </tr>
        </table>
    </div>
    <div ng-if="moreItemsCanBeLoaded()" class="stats-load animate-loading ion-load-d"></div>
</div>

