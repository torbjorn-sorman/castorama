<!DOCTYPE html>
<html ng-app="castorama">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width" />

    <title>CASTORAMA.SE</title>
    <script src="assets/lib/ionic/js/ionic.bundle.js"></script>
    <script type="text/javascript" src="/assets/js/angular/angular.js"></script>
    <script type="text/javascript" src="/assets/js/angular/angular-route.js"></script>
    <script type="text/javascript" src="/assets/js/services.js"></script>
    <script type="text/javascript" src="/assets/js/controllers.js"></script>
    <script type="text/javascript" src="/assets/js/filters.js"></script>
    <script type="text/javascript" src="/assets/js/app.js"></script>
    <script type="text/javascript" src="/assets/js/models/castorama.js"></script>
    <link rel="stylesheet" type="text/css" href="/assets/lib/ionic/css/ionic.min.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/ionicons.css" />
</head>
<body ng-controller="MainController">
    <div id="topbar">
        <h1 class="castorama-font">CASTORAMA.SE</h1>
        <button class="ion-calculator" ng-click="navigate('home')"></button>
        <button class="ion-stats-bars" ng-click="navigate('stats')"></button>
        <button ng-if="status.loggedin" class="ion-soup-can" ng-click="navigate('parser')"></button>
        <button ng-if="status.loggedin" class="ion-log-out" ng-click="logout()"></button>
    </div>
    <div class="content" ng-view></div>
    <div id="bottombar">
        <div class="event-display">
            <img src="assets/img/events/shot.png" />
            <img src="assets/img/events/javelin.png" />
            <img src="assets/img/events/discus.png" />
            <img src="assets/img/events/hammer.png" />
        </div>
        <p>
            <span class="castorama-font-small">CASTORAMA.SE</span>
            <button class="button-small ion-wand" ng-click="navigate('admin')"></button>
        </p>
        <em>Allt statistikmaterial tillh&ouml;r Svenska Friidrottsf&ouml;rbundet. Refererar till <a target="_blank" href="http://www.friidrott.se/rs/resultat2/castarkiv/intro.aspx">castorama resultatarkiv</a>. Anv&auml;nd alltid Svenska Friidrottsf&ouml;rbundets resultat och statistik f&ouml;r officiella resultat.
            <br />
            Reservationer f&ouml;r felaktigheter, h&ouml;r g&auml;rna av dig till <a href="mailto:kontakt@castorama.se">kontakt@castorama.se</a> f&ouml;r korrigering.
        </em>
        <br />
    </div>
</body>
</html>
