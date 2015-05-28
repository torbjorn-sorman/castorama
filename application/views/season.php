<h1>&Aring;rets resultat</h1>
<div class="drop-down">
    <div class="drop-down-head" ng-click="toggleMen()">
        <div class="drop-down-title">M&auml;n</div>
        <div class="drop-down-toggle {{showMen ? 'ion-chevron-up' : 'ion-chevron-down'}}"></div>
    </div>
    <div ng-if="showMen" class="stats-options">
        <table>
            <tr class="row-item" ng-repeat="r in result.men">
                <td>{{$index + 1}}</td>
                <td>{{r.name}}</td>
                <td>{{r.score}}</td>
            </tr>
        </table>
    </div>
</div>


<div class="drop-down">
    <div class="drop-down-head" ng-click="toggleWomen()">
        <div class="drop-down-title">Kvinnor</div>
        <div class="drop-down-toggle {{showWomen ? 'ion-chevron-up' : 'ion-chevron-down'}}"></div>
    </div>
    <div ng-if="showWomen" class="stats-options">
        <table>
            <tr class="row-item" ng-repeat="r in result.women">
                <td>{{$index + 1}}</td>
                <td>{{r.name}}</td>
                <td>{{r.score}}</td>
            </tr>
        </table>
    </div>
</div>

<div class="drop-down">
    <div class="drop-down-head" ng-click="toggleClub()">
        <div class="drop-down-title">Klubblag</div>
        <div class="drop-down-toggle {{showClub ? 'ion-chevron-up' : 'ion-chevron-down'}}"></div>
    </div>
    <div ng-if="showClub" class="stats-options">
        <table>
            <tr class="row-item" ng-repeat="r in result.club track by $index">
                <td>{{$index + 1}}</td>
                <td>{{r.club}}</td>
                <td>{{r.score}}</td>
            </tr>
        </table>
    </div>
</div>

