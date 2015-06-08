
angular.module('castorama.directives', [])

.directive('statsRecord', function () {
    return {
        restrict: 'E',
        scope: {
            records: '=records'
        },
        templateUrl: '/templates/get/stats-record',
        link: function($scope, element, attrs) {
            $scope.toMeter = function(val) {
                if (val == 0)
                    return "";
                var text = val, fixed;
                return text.replace(/(^\d?\d)(\d\d)/, "$1,$2");
            }
        }
    };
});