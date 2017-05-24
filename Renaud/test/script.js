(function(angular) {
  'use strict';
angular.module('ngRouteExample', ['ngRoute'])

 .controller('ChapterController', function($scope, $routeParams) {
     $scope.name = 'ChapterController';
     $scope.params = $routeParams;
 })
.factory('stocksFactory',function(){
        
    var stocks = {};

    stocks.stock = [ 
            {id:1, designation:'Ecran', categorie:'A', marque:'LG', numero:123, dispo:true, info:'glyphicon glyphicon-info-sign', supprimmer: 'glyphicon glyphicon-trash'},
            {id:2, designation:'Souris', categorie:'C', marque:'DELL', numero:628, dispo:false, info:'glyphicon glyphicon-info-sign', supprimer:'glyphicon glyphicon-trash'},
            {id:3, designation:'Ordiateur', categorie:'B', marque:'ACER', numero:400, dispo:false, info:'glyphicon glyphicon-info-sign', supprimer:'glyphicon glyphicon-trash'},
        ];
    return stocks;
})
.controller('stocksController',['$scope','stocksFactory', '$log', 'orderByFilter', function($scope, stocksFactory, $log, orderBy ){
    $scope.stocks = stocksFactory.stock;
    $scope.propertyName = "designation";
    $scope.reverse  = false;
    

    $scope.sortBy = function(propertyName) {
        $scope.reverse  = (propertyName !== null && $scope.propertyName === propertyName)
        ? !$scope.reverse  : true;
        $scope.propertyName = propertyName;
    };
}])
    
.controller('stockController',['$scope','stocksFactory', '$log', 'orderByFilter', function($scope, stocksFactory, $log, orderBy ){
    
}])

.config(function($routeProvider, $locationProvider) {
  $routeProvider
   .when('/stocks', {
    templateUrl: '../app/view/stocks.html',
    controller: 'stocksController'
  })
  .otherwise({redirectTo:'/'});

  // configure html5 to get links working on jsfiddle
  $locationProvider.html5Mode(true);
});
})(window.angular);

/*
Copyright 2017 Google Inc. All Rights Reserved.
Use of this source code is governed by an MIT-style license that
can be found in the LICENSE file at http://angular.io/license
*/