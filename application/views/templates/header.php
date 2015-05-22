<!DOCTYPE html>
<html ng-app="LIFnewsLive">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width" />

    <title>Castorama</title>
    <script type="text/javascript" src="/assets/js/moment/moment.js"></script>
    <script type="text/javascript" src="/assets/js/angular/angular.js"></script>
    <script type="text/javascript" src="/assets/js/angular/angular-route.js"></script>
    <script type="text/javascript" src="/assets/js/app.js"></script>
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css" />
</head>
<body>
    <div id="topbar">
        <h1 class="castorama-font">LIFnews Live</h1>
        <a href="#home">
            <img class="icon" src="assets/svg/home.svg" alt="Home" title="Home" /></a>
        <!--
        <a href="#report">
            <img class="icon" src="assets/svg/edit.svg" alt="Report" title="Report" /></a>
        <a href="#games">
            <img class="icon" src="assets/svg/calendar.svg" alt="Games" title="Games" /></a>
        <a href="#teams">
            <img class="icon" src="assets/svg/users.svg" alt="Teams" title="Teams" /></a>
            -->
        <a href="logout">
            <img class="icon" src="assets/svg/log-out.svg" alt="Log Out" title="Log Out" /></a>
    </div>
    <div ng-view></div>
