<div class="parser-category">
    <div ng-if="loading" class="parser-load animate-loading ion-load-d"></div>
    <h2>Uppdatera</h2>
    <button class="parser-start-button ion-document-text" ng-click="updateDB(year)">&nbsp;Starta</button>
    <p>V&auml;lj &aring;r:
        <select ng-model="year" ng-options="y for y in [] | range:2001:2014"></select></p>
    
    <div ng-if="response.update.message">
        <textarea rows="3" cols="40">{{response.update.text}}</textarea>
    </div>
</div>
<div class="parser-category">
    <div ng-if="loading" class="parser-load animate-loading ion-load-d"></div>
    <h2>Skapa s&auml;song</h2>
    <button class="parser-start-button ion-document-text" ng-click="updateSeason(season)">&nbsp;Starta</button>
    
    <p>V&auml;lj &aring;r:
        <select ng-model="season" ng-options="y for y in [] | range:2001:2014"></select></p>
    
    <div ng-if="response.season.message">
        <textarea rows="3" cols="40">{{response.season.text}}</textarea>
    </div>
</div>
<div class="parser-category">
    <div ng-if="loading" class="parser-load animate-loading ion-load-d"></div>
    <h2>R&auml;kna po&auml;ng</h2>
    <button class="parser-start-button ion-document-text" ng-click="updateScore()">&nbsp;Starta</button>    
    <div ng-if="response.score.message">
        <textarea rows="3" cols="40">{{response.score.text}}</textarea>
    </div>
</div>
