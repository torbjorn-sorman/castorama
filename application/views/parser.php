<h1>Parsning</h1>
<div ng-if="loading">
    <span class="castorama-font">LOADING!!!</span>
</div>
<div ng-if="!loading">
    <select ng-model="options.year" ng-options="y for y in [] | range:2001:2014"></select>
    <p>
        <button class="button-start ion-document-text" ng-click="updateDB(options)">Parsa {{options.year}}</button>
    </p>
</div>
