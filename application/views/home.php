<div class="score-gender">
    <button class="score-man ion-man {{ castorama.gender.toggle ? 'selected' : '' }}" ng-click="genderSelect('men')"></button>
    <button class="score-woman ion-woman {{ !castorama.gender.toggle ? 'selected' : '' }}" ng-click="genderSelect('women')"></button>
</div>
<table class="score">
    <tr ng-repeat="e in ['shot', 'javelin', 'discus', 'hammer']">
        <td class="event">
            <img class="event-icon" ng-src="{{castorama.events[e].name}}" />
        </td>
        <td class="result">
            <input type="number" ng-model="castorama.events[e].result" step="1" placeholder="(cm)" />
        </td>
        <td class="score number-display">
            <span class="highlighted-text">{{castorama.events[e].score}}</span>
        </td>
    </tr>
</table>
<div class="score-output">
    <span class="highlighted-text">{{castorama.sum.value}}</span>
</div>
