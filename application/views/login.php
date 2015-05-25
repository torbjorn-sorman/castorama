<div class="admin-background">
    <h1>Administrering</h1>
    <form ng-if="!status.loggedin">
        <p>
            <input type="text" size="25" ng-model="login.username" placeholder="Anv&auml;ndarnamn" /></p>
        <p>
            <input type="password" size="25" ng-model="login.password" placeholder="L&ouml;senord" /></p>
        <p>
            <input type="submit" value="Logga In" ng-click="verifyLogin(login)" /></p>
    </form>
    <div ng-if="status.loggedin">
        <h2>Inloggad som {{status.username}}</h2>
    </div>
</div>
