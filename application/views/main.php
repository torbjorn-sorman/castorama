<!DOCTYPE html>
<html ng-app="castorama">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width" />

    <title>Castorama.se</title>
    <script type="text/javascript" src="/assets/js/ext/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" src="/assets/js/angular/angular.js"></script>
    <script type="text/javascript" src="/assets/js/angular/angular-route.js"></script>
    <script type="text/javascript" src="/assets/js/services.js"></script>
    <script type="text/javascript" src="/assets/js/controllers.js"></script>
    <script type="text/javascript" src="/assets/js/filters.js"></script>
    <script type="text/javascript" src="/assets/js/app.js"></script>
    <script type="text/javascript" src="/assets/js/models/castorama.js"></script>

    <script type="text/javascript" src="/assets/js/ext/ng-infinite-scroll.min.js"></script>

    <link rel="stylesheet" type="text/css" href="/assets/css/castorama.css" />

</head>
<body ng-controller="MainController">
    <div id="container">
        <div id="header">
            <h1 class="castorama-font">CASTORAMA.SE</h1>
            <div class="bordered">
                <div class="nav-main {{ status.nav == 0 ? 'nav-sel' : '' }} ion-calculator" ng-click="navigate('home', 0)">&nbsp;Ber&auml;kna</div>
                <div class="nav-main {{ status.nav == 1 ? 'nav-sel' : '' }} ion-stats-bars" ng-click="navigate('stats', 1)">&nbsp;Statistik</div>
                <div class="nav-main {{ status.nav == 2 ? 'nav-sel' : '' }} ion-trophy" ng-click="navigate('season', 2)">&nbsp;&Aring;rets</div>
                <div class="nav-main {{ status.nav == 3 ? 'nav-sel' : '' }} ion-soup-can" ng-click="navigate('parser', 3)" ng-if="status.loggedin">&nbsp;Databas</div>
                <div class="nav-main-user ion-log-in" ng-click="navigate('admin')" ng-if="!status.loggedin">&nbsp;Logga in</div>
                <div class="nav-main-user ion-log-out" ng-click="logout()" ng-if="status.loggedin">&nbsp;Logga ut</div>
            </div>
        </div>
        <div id="content" ng-view></div>
        <div id="footer">
            <p>
                <span class="castorama-font-small">CASTORAMA.SE</span>
                <button class="button-discreet ion-wand" ng-click="navigate('admin')"></button>
                <img class="icon-small" src="assets/img/events/shot.png" />
                <img class="icon-small" src="assets/img/events/javelin.png" />
                <img class="icon-small" src="assets/img/events/discus.png" />
                <img class="icon-small" src="assets/img/events/hammer.png" />
            </p>
            <em>Allt statistikmaterial tillh&ouml;r Svenska Friidrottsf&ouml;rbundet. Refererar till <a target="_blank" href="http://www.friidrott.se/rs/resultat2/castarkiv/intro.aspx">castorama resultatarkiv</a>.
                <br />
                Anv&auml;nd alltid Svenska Friidrottsf&ouml;rbundets resultat och statistik f&ouml;r officiella resultat.
                <br />
                Reservationer f&ouml;r felaktigheter, h&ouml;r g&auml;rna av dig till <a href="mailto:kontakt@castorama.se">kontakt@castorama.se</a> f&ouml;r korrigering.
            </em>
            <br />
        </div>
    </div>
</body>
</html>
