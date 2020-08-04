var min = 50; // the min amount;

var app = angular.module('formApp', ['ngMessages']);
  app.factory('formData', function ($http) {
    return {
        get: function () {
            return $http.get(fileName);

        }
    };
  });
  app.directive('validFile', function () {
  return {
    restrict: 'A',
    require: '?ngModel',
    link: function (scope, el, attrs, ngModel) {
      el.bind('change', function () {
        scope.$apply(function () {
          ngModel.$setViewValue(el.val());
        });
      });
    }
  };
});
app.directive('input', [function() {
    return {
        restrict: 'E',
        require: '?ngModel',
        link: function(scope, element, attrs, ngModel) {
            if (
                   'undefined' !== typeof attrs.type
                && 'number' === attrs.type
                && ngModel
            ) {
                ngModel.$formatters.push(function(modelValue) {
                    return Number(modelValue);
                });

                ngModel.$parsers.push(function(viewValue) {
                    return Number(viewValue);
                });
            }
        }
    }
}]);
app.controller('formController', function ($scope, formData) {
    
    if(fileName){
      formData.get().then(function(res){
        $scope.data = res.data;               
      });
    };
   
    $scope.widthCh = function(){
      ($scope.data || {}).amount = Math.round(($scope.data.width * $scope.data.height));
      $scope.min = min;
      if($scope.data.amount < $scope.min){
        $scope.paypal.amount.min = true;
      }else{
        $scope.paypal.amount.min = false;
      }
    }
    $scope.heightCh = function(){
      ($scope.data || {}).amount = Math.round(($scope.data.width * $scope.data.height));
      $scope.min = min;
      if($scope.data.amount < $scope.min){
        $scope.paypal.amount.min = true;
      }else{
        $scope.paypal.amount.min = false;
      }
    }
    
    $scope.validatePayPal = function () {

      angular.forEach($scope.paypal.$error.required, function(field) {
        field.$setDirty();
      });

      $scope.formSubmited = true;
  };
});