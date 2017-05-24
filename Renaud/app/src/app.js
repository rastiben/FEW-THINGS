(function(angular) {
  'use strict';
var Appli = angular.module('Appli',['ngRoute'])

.config(function($routeProvider, $locationProvider) { // ROUTE PROVIDER (Configuration des adressses)
    $routeProvider
    .when('/stocks', {
        templateUrl: './app/view/stocks.html', // ADRESSE LISTE
        controller: 'stocksController'
    })
    .when('/stock/:id', {
        templateUrl: './app/view/stock.html', // ADRESSE HISTORIQUE
        controller: 'stockController'
    })
    .otherwise({redirectTo:'/stocks'}); // ADDRESSE PRIMAIRE
    
    
    $locationProvider.html5Mode(true); // CE CODE PERMET DE MODIFIER LES ADDREESE EN HTML POUR NE PAS AVOIR /localhost/#! MAIS /localhost/
})

.factory('stocksFactory', function(){ //MODULE
        
    var stocks = {};

    //[{},{},{},...];   CREATION DES OBJETS
    stocks.stock = [ 
            {
            id:1, 
            designation:'Ecran', 
            categorie:'A', 
            marque:'LG', 
            numero:123, 
            dispo:true,
                
            historique:[ // TABLEAU D'OBJET (Historique) DANS UN TABLEAU D'OBJET (Stock)
                        {
                        id_histo:1, 
                        organisation:'Evilcorp', 
                        destinataire:'mpkjv', 
                        initiale_tech:'JP', 
                        raison_pret: 'usure',
                        date:'25-05-2019'
                        }
                        ]
            },
        
            {
            id:2,
            designation:'Souris',
            categorie:'C',
            marque:'DELL',
            numero:628,
            dispo:false,
             
            historique:[
                        {
                        id_histo:2,
                        organisation:'FSociety',
                        destinataire:'brad',
                        initiale_tech:'BD',
                        raison_pret: 'casse',
                        date:'14-09-2025'      
                        }
                        ]
                 
            },
        
            {
            id:3,
            designation:'Ordiateur',
            categorie:'B',
            marque:'ACER',
            numero:400,
            dispo:false,
             
            historique:[
                       {
                       id_histo:3,
                       organisation:'REZAT',
                       destinataire:'walter',
                       initiale_tech:'WR',
                       raison_pret: 'casse',
                       date:'14-10-2018'
                       }
                       ]
            }
            ];
           

            stocks.delete = function(stock) { 
                stocks.stock.splice(stocks.stock.indexOf(stock), 1);
            }
            
            return stocks;
    
})
//CONTROLLEUR QUI PERMET DANS TRIER DE A a Z
.controller('stocksController',['$scope','stocksFactory', '$log', 'orderByFilter', function($scope, stocksFactory, $log, orderBy ){
    $scope.stocks = stocksFactory.stock;
    $scope.propertyName = "designation";  // LE PROPERTY NAME EST DESIGNATION
    $scope.reverse  = false;              // IL EST DE BASE FAUX
    
    $scope.delete = function(stock){
        stocksFactory.delete(stock);
    }
    
    $scope.addForm = false;
    
    $scope.displayForm=function() { 
        $scope.addForm=!$scope.addForm;
    }
    
    /*$scope.sortBy = function(addForm) {
    $scope.OFform  = (addForm == true && )                   
    ? $scope.addForm  : false;                                   
    
    };*/
    
    $scope.addLine = function(index) {
      $scope.stocks.push({
            id:$scope.stocks[$scope.stocks.length-1].id+1,
            designation:$scope.designation,
            categorie:$scope.categorie,
            marque:$scope.marque,
            numero:$scope.numero,
            dispo:$scope.dispo,
      });
        
    }
    

    
    
    $scope.sortBy = function(propertyName) {
        $scope.reverse  = (propertyName !== null && $scope.propertyName === propertyName) //ON POSE UN QUESTION (?) EST CE QUE PROPERTYNAME N'EST PAS NUL 
        ? !$scope.reverse  : true;                                                        //ET QUE C'EST DESIGNATION ALORS L'INVERSE IL RESTE FAUX  
        $scope.propertyName = propertyName;
    };
}])
//CONTROLEUR QUI PERMET D'ALLER DANS L'ID 
.controller('stockController',['$scope','stocksFactory', '$log', 'orderByFilter', '$routeParams', '$filter', function($scope, stocksFactory, $log,  orderBy, $routeParams, $filter){
    var id = $routeParams.id; 
    $scope.stock = $filter('filter')(stocksFactory.stock, {'id':id})[0]; // FILTRAGE DU MODULE STOCKSFACTORY (soit 1 ; 2 ; 3) POUR FAIRE CORRESPONDRE 
    $scope.historique = $scope.stock.historique;                         // L'HISTORIQUE AVEC LE MATERIEL (stock/1 = info objet 1)
}]);                                                                     //                               (stock/2 = info objet 2)
    
})(window.angular);









