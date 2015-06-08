<table class="stats-table">
    <colgroup>
        <col span="1" style="width: 35%;" />
        <col span="5" style="width: 13%;" />
    </colgroup>
    <thead>
        <tr>
            <th></th>
            <th>Po&auml;ng</th>
            <th><img class="icon-small" src="assets/img/events/shot.png" /></th>
            <th><img class="icon-small" src="assets/img/events/javelin.png" /></th>
            <th><img class="icon-small" src="assets/img/events/discus.png" /></th>
            <th><img class="icon-small" src="assets/img/events/hammer.png" /></th>
        </tr>
    </thead>
    <tbody>
        <tr ng-repeat="r in records">
            <td>
                <p class="stats-date-location">{{r.date}}, {{r.location}}</p>
                <p class="stats-name-club">{{r.name + (r.birthyear != 0 ? ' -' + r.birthyear : '')}}, {{r.club}}</p>
            </td>
            <td>{{r.score}}</td>
            <td>{{toMeter(r.shot)}}</td>
            <td>{{toMeter(r.javelin)}}</td>
            <td>{{toMeter(r.discus)}}</td>
            <td>{{toMeter(r.hammer)}}</td>
        </tr>
    </tbody>
</table>
