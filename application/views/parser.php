<h1>Uppdatera</h1>
<div ng-if="loading" class="loading-spinner"></div>
<div ng-if="!loading">
    <select ng-model="options.year" ng-options="y for y in [] | range:2001:2014"></select>
    <p>
        <button class="button-start ion-document-text" ng-click="updateDB(options)"> L&auml;s in {{options.year}}</button>
    </p>
</div>
<div ng-if="response.message">
    <h2>Antal nya poster</h2>
    <p>M&auml;n: {{response.posts.men}}</p>
    <p>Kvinnor: {{response.posts.women}}</p>
</div>
