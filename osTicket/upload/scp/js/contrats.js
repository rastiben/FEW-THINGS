moment.locale('fr');

angular.module('myApp').requires.push('ngResource');

app.factory('contratFactory',['$resource',function($resource){

  return $resource('ajax.php/contrats/:contratId',{contratId:'@id'});

}]);

app.controller('contratCtrl',['$scope','contratFactory','$log',function($scope,contratFactory,$log){

  $scope.contrats = contratFactory.query();

  $scope.save = function(vars){
    var contrat = new contratFactory();
    //contrat = vars;
    contrat.code = vars['code'];
    contrat.org = vars['org'];
    contrat.client = vars['client'];
    contrat.etat = vars['etat'];
    contrat.date_debut = moment(vars['date_debut'],'DD/MM/YYYY').format('YYYY-MM-DD');
    contrat.date_fin = moment(vars['date_fin'],'DD/MM/YYYY').format('YYYY-MM-DD');
    contrat.type = vars['type'];
    contrat.created = moment(vars['created'],'DD/MM/YYYY').format('YYYY-MM-DD');

    contrat.$save();
    $scope.contrats.push(angular.copy(contrat));
  }

}]);

app.directive('datepicker', function() {
  return {
    require: 'ngModel',
    link: function(scope, el, attr, ngModel) {
      $(el).datepicker({
          startView: 1,
          dateFormat: 'dd/mm/yy',
          autoclose: true
      });
    }
  };
});

app.directive('modal', function () {
    return {
        restrict: 'EA',
        scope: {
            header: '@',
            body: '@',
            footer: '@',
            callbackbuttonleft: '&ngClickLeftButton',
            callbackbuttonright: '&ngClickRightButton',
            handler: '=lolo'
        },
        templateUrl: './templates/modal.html',
        transclude: true,
        controller: function ($scope) {
            $scope.handler = 'pop';
        },
    };
});

//filtre de formatage de moment date
app.filter('mFormat', function() {
    return function(input, format) {
      return (!!input) ? moment(input).format(format) : '';
    }
});
