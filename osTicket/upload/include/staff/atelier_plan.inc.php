<?php

?>
    <div class="plan">
       <h1>Plan de l'atelier : </h1>
        <div class="atelier">
            <div class="bureau" id="un"></div>
            <div class="bureau" id="deux"></div>
            <div class="bureau" id="trois"></div>
            <div class="portable" id="un"></div>
            <div class="portable" id="deux"></div>
            <div class="portable" id="trois"></div>
            <div class="mur" id="un"></div>
            <div class="mur" id="deux"></div>
            <div class="mur" id="trois"></div>
            <div class="mur" id="quatre"></div>
            <div class="serveur" id="un"></div>
            <div class="serveur" id="deux"></div>
            <div class="serveur" id="trois"></div>
            <div class="serveur" id="quatre"></div>
            <div class="serveur" id="cinq"></div>
            <div class="serveur" id="six"></div>
        </div>

        <div ng-style="{display:displayFiche}" ng-controller="ficheCrtl">

        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.1/angular.min.js"></script>

    <script>
        var myApp = angular.module('myApp',[]);

        myApp.service('ficheSrvc', function($http) {
            delete $http.defaults.headers.common['X-Requested-With'];
            this.getData = function(callbackFunc) {
                $http({
                    method: 'GET',
                    url: 'https://www.example.com/api/v1/page',
                    params: 'limit=10, sort_by=created:desc',
                    headers: {'Authorization': 'Token token=xxxxYYYYZzzz'}
                }).success(function(data){
                    // With the data succesfully returned, call our callback
                    callbackFunc(data);
                }).error(function(){
                    alert("error");
                });
             }
        });

        myApp.controller('ficheCrtl', function($scope, ficheSrvc) {
            $scope.data = null;
            $scope.displayFiche = 'none';
            /*ficheSrvc.getData(function(dataResponse) {
                $scope.data = dataResponse;
            });*/
            $scope.posteClicked = function(){

            }
        });

    </script>




