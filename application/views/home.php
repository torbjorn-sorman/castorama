
<div class="scoretable-gender-selection">
    <button class="gender-left button-gender ion-man {{ castorama.gender.toggle ? 'selected' : '' }}" ng-click="genderSelect('men')"></button>
    <button class="gender-right button-gender ion-woman {{ !castorama.gender.toggle ? 'selected' : '' }}" ng-click="genderSelect('women')"></button>
</div>
<div class="calc-output">
    <span class="number-display-large highlighted-text">{{castorama.sum.value}}</span>
</div>
<table>
    <tr ng-repeat="e in ['shot', 'javelin', 'discus', 'hammer']">
        <td class="event">
            <img class="event-icon" ng-src="{{castorama.events[e].name}}" />
        </td>
        <td class="result style-5">
            <input type="number" ng-model="castorama.events[e].result" step="1" placeholder="(cm)" />
        </td>
        <td class="score number-display"><span class="highlighted-text">{{castorama.events[e].score}}</span>
        </td>
    </tr>
</table>
