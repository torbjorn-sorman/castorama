<!DOCTYPE html>
<html ng-app="castorama">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width" />

    <title>CASTORAMA.SE</title>
    <script type="text/javascript" src="/assets/js/angular/angular.js"></script>
    <script type="text/javascript" src="/assets/js/angular/angular-route.js"></script>
    <script type="text/javascript" src="/assets/js/app.js"></script>
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/ionicons.css" />
</head>
<body ng-controller="MainController">
    <div id="topbar">
        <h1 class="castorama-font">CASTORAMA.SE</h1>
        <button class="ion-calculator" ng-click="navigate('home')"></button>
        <button class="ion-stats-bars" ng-click="navigate('stats')"></button>        
    </div>
    <div ng-view></div>
    <div id="bottombar">
        <em>Allt statistikmaterial tillh&ouml;r Svenska Friidrottsf&ouml;rbundet.</em>
        <br />
        <button class="button-small ion-wand" ng-click="navigate('admin')"></button>
    </div>
</body>
</html>
