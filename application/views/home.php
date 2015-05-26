<div class="row">
    <div class="col col-25 col-offset-25">
        <button class="button button-icon button-gender ion-man {{ castorama.gender.toggle ? 'selected' : '' }}" ng-click="genderSelect('men')"></button>
    </div>
    <div class="col col-25">
        <button class="button button-icon button-gender ion-woman {{ !castorama.gender.toggle ? 'selected' : '' }}" ng-click="genderSelect('women')"></button>
    </div>
</div>
<div class="row" ng-repeat="e in ['shot', 'javelin', 'discus', 'hammer']">
    <div class="col col-25">
        <img class="event-icon" ng-src="{{castorama.events[e].name}}" />
    </div>
    <div class="col col-33 style-5">
        <input type="number" ng-model="castorama.events[e].result" step="1" placeholder="(cm)" />
    </div>
    <div class="col col-33 col-offset-10 number-display">
        {{castorama.events[e].score}}
    </div>
</div>
<div class="row">
    <div class="col col-25"></div>
    <div class="col col-33"></div>
    <div class="col col-33 col-offset-10 number-display">{{castorama.sum.value}}</div>
</div>
