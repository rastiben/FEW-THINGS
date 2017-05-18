moment.locale('fr');

angular.module('myApp').requires.push('ngResource');
angular.module('myApp').requires.push('ngMaterial');

app.factory('contratFactory',['$resource',function($resource){

  return $resource('ajax.php/contrats/:contratId',{contratId:'@id'});

}]);

app.controller('contratCtrl',['$scope','contratFactory','$log',function($scope,contratFactory,$log){

  $scope.header = "Ajout d'un contrat";
  $scope.valid = "Créer le contrat";

  $scope.initContratData = function(contrat){
    /*init date*/
    contrat.date_debut = moment(contrat.date_debut,'YYYY-MM-DD').format('DD/MM/YYYY');
    contrat.date_fin = moment(contrat.date_fin,'YYYY-MM-DD').format('DD/MM/YYYY');
    contrat.created = moment(contrat.created,'YYYY-MM-DD').format('DD/MM/YYYY');

    /*Ajout de la prochaine date*/
    contrat.nextDate = moment(contrat.date_fin,'DD-MM-YYYY').add(1,'d').format('DD/MM/YYYY');
  }

  $scope.contrats = contratFactory.query(function(contrats){
    angular.forEach(contrats,function(contrat,key){
      $scope.initContratData(contrat);
    });
  });

  $scope.propertyName = 'code';
  $scope.reverse = false;

  $scope.sortBy = function(propertyName) {
    $scope.reverse = ($scope.propertyName === propertyName) ? !$scope.reverse : true;
    $scope.propertyName = propertyName;
  };

  $scope.save = function(vars){
    if($scope.action == "create")
      var contrat = new contratFactory();
    else
      var contrat = angular.copy($scope.contrat);

      contrat.code = vars['code'];
      contrat.org = vars['org'];
      contrat.client = vars['client'];
      contrat.etat = vars['etat'];
      contrat.date_debut = moment(vars['date_debut'],'DD/MM/YYYY').format('YYYY-MM-DD');
      contrat.date_fin = moment(vars['date_fin'],'DD/MM/YYYY').format('YYYY-MM-DD');
      contrat.type = vars['type'];

    //if($scope.action == "create")
      //contrat.created = moment().format('YYYY-MM-DD');

      contrat.$save(function(contrat){
        $scope.initContratData(contrat);
        if($scope.action == "create")
          $scope.contrats.push(angular.copy(contrat));
      });
    }

    $scope.remove = function(contrat){
      contrat.$remove(function(){
        var index = $scope.contrats.indexOf(contrat);
        $scope.contrats.splice(index,1);
      });
    }

  $scope.modalInfo = function(header,valid,contrat,action){
    $scope.header = header;
    $scope.valid = valid;
    $scope.contrat = contrat;
    $scope.action = action;
  }

}]);

app.directive('datepicker', function() {
  return {
    require: 'ngModel',
    link: function(scope, el, attr, ngModel) {
      $.datepicker.regional['fr'] = {
    		closeText: 'Fermer',
    		prevText: 'Précédent',
    		nextText: 'Suivant',
    		currentText: 'Aujourd\'hui',
    		monthNames: ['Janvier','Février','Mars','Avril','Mai','Juin',
    		'Juillet','Août','Septembre','Octobre','Novembre','Décembre'],
    		monthNamesShort: ['Janv.','Févr.','Mars','Avril','Mai','Juin',
    		'Juil.','Août','Sept.','Oct.','Nov.','Déc.'],
    		dayNames: ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'],
    		dayNamesShort: ['Dim.','Lun.','Mar.','Mer.','Jeu.','Ven.','Sam.'],
    		dayNamesMin: ['D','L','M','M','J','V','S'],
    		weekHeader: 'Sem.',
    		dateFormat: 'dd/mm/yy',
    		firstDay: 1,
    		isRTL: false,
    		showMonthAfterYear: false,
    		yearSuffix: ''};
    	$.datepicker.setDefaults($.datepicker.regional['fr']);
      var datepicker = $(el).datepicker({
          startView: 1,
          dateFormat: 'dd/mm/yy',
          autoclose: true
      });
      if($(el).attr('id') == "date_debut"){
        datepicker.on("input change",function(e){
          scope.$apply(function(){
            scope.contrat.date_fin = moment(scope.contrat.date_debut,'DD/MM/YYYY').add(1,'y').format('DD/MM/YYYY');
            if(scope.contrat.org != undefined)
              scope.contrat.code = moment(scope.contrat.date_debut,'DD/MM/YYYY').format('YYYYMM') + scope.contrat.org;
            scope.contrat.date_prochaine_facture = scope.contrat.date_debut;
          });
        });
      }
    }
  };
});

app.directive('modal',['$http', function ($http) {
    return {
        restrict: 'EA',
        scope: {
            header: '@',
            body: '@',
            footer: '@',
            validButton : '@valid',
            contrat : '=',
            callbackbuttonleft: '&ngClickLeftButton',
            callbackbuttonright: '&ngClickRightButton',
            handler: '=lolo'
        },
        templateUrl: './templates/modal.html',
        transclude: true,
        controller: function ($scope) {
            $scope.handler = 'pop';
            /*$scope.updateEnd = function(){
              $scope.contrat.date_fin = $scope.contrat.date_fin.add(1,'y');
            }*/
            $scope.updateCode = function(){
              if(scope.contrat.date_debut != undefined)
                $scope.contrat.code = moment(scope.contrat.date_debut,'DD/MM/YYYY').format('YYYYMM') + scope.contrat.org;
            }
        },
        link: function(scope, element, attrs){
          $http.get('ajaxs.php/contrats/typeahead').then(function(data){
            scope.contratTypes = data.data;
            $("#type #default").remove();
          });
        }
    };
}]);

app.directive('typeahead', function () {
  return {
    restrict: 'A',
    scope: {
      url:'='
    },
    link: function(scope, element, attrs){
      $(element).typeahead({
          source: function (typeahead, query) {
              $.ajax({
                  url: scope.url + $(element).val(),
                  dataType: 'json',
                  success: function (data) {
                      typeahead.process(data);
                  }
              });
          },
          onselect: function (obj) {
              $(element).val(obj.id);
          },
          property: "/bin/true"
      });
    }
  }
});



//filtre de formatage de moment date
app.filter('mFormat', function() {
    return function(input, format) {
      return (!!input) ? input.format(format) : '';
    }
});
