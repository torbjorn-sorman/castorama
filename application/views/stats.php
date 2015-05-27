<p>
    <div class="drop-down" ng-click="toggleOptions()">
        <div class="drop-down-title">Alternativ</div>
        <div class="drop-down-toggle {{showOptions ? 'ion-chevron-up' : 'ion-chevron-down'}}"></div>
    </div>
    <div ng-if="showOptions" class="stats-options">
        <p>Alternativ 1</p>
        <p>Alternativ 2</p>
        <p>Alternativ 3</p>
    </div>
</p>
<p>
    <div class="drop-down" ng-click="toggleSearch()">
        <div class="drop-down-title">S&ouml;k</div>
        <div class="drop-down-toggle {{showOptions ? 'ion-chevron-up' : 'ion-chevron-down'}}"></div>
    </div>
    <div ng-if="showSearch" class="stats-search">
        <input type="text" placeholder="Namn" />
        <input type="text" placeholder="Klubb" />
        <input type="text" placeholder="Plats" />
        <input type="submit" value="S&ouml;k" />
    </div>
</p>
<p>
    <div class="drop-down">
        <div class="drop-down-title">Resultat</div>        
    </div>
</p>
<div>
    <table class="stats-table">
        <tr class="row-header">
            <th>date</th>
            <th>location</th>
            <th>name</th>
            <th>club</th>
            <th>score</th>
            <th>shot</th>
            <th>javelin</th>
            <th>discus</th>
            <th>hammer</th>
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
<div ng-if="moreItemsCanBeLoaded()">
    <button ng-click="addMoreItems()">Ladda fler resultat</button>
</div>
