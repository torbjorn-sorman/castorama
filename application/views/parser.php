<h1>Parsning</h1>
<select ng-model="options.year" ng-options="y for y in [] | range:2001:2014"></select>
<p>
    <button class="ion-document-text" ng-click="updateDB(options)"></button>
</p>
