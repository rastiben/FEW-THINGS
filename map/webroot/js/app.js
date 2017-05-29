var app = angular.module("mapAngular",["ngResource"])

.factory("mapFactory",['$resource',function($resource){
    
    return $resource("Markers.json", {id:'@_id'}, {
        query : {
            method:'GET',
            url:'Markers.json',
            isArray:false
        }
    });
    /*$.ajax({
        url: "Markers.json",
        type: "GET",
        success: function(data) {
            
        }
    });*/
}])

.controller("mapController",["$scope",function($scope){
    
    $scope.showInfoForm = false;
    
    //$scope.marker = undefined;
    
    $scope.switchInfoForm = function(){
        $scope.$apply(function(){
           $scope.showInfoForm = !$scope.showInfoForm; 
        });
    }
    
}])

.directive("leaflet",["$http","mapFactory",function($http,mapFactory){
   return {
        restrict: 'EA',
        template: '<div id="map">',
        scope : {
            showInfoForm: '=',
            switchInfoForm: '&'
        },
        transclude: true,
        controller: function ($scope) {
                      
            $scope.markers = [];
            $scope.layer = undefined;
            
            mapFactory.query(function(markers){
                
                var temp = [];
                        
                angular.forEach(markers.markers,function(marker,key){

                    var icon = undefined;
                    switch(marker.constructeur){
                        case "Maison d'aujourd'hui":
                            icon = blueIcon;
                            break;
                        case "Demeures & Cottages":
                            icon = purpleIcon;
                            break;
                        case "Maison sweet":
                            icon = orangeIcon;
                            break;
                    }

                    if(marker.dispo)
                        temp.push(L.marker([marker.lat, marker.lng], {icon: icon}).on('click', $scope.onClick));
                    $scope.markers.push(marker);
                });

                $scope.layer = new L.FeatureGroup(temp).addTo($scope.map);
                
            });
            
            $scope.onClick = function(e) {
                var arr = $.grep($scope.markers, function(value,key) {
                  return value.lat == e.latlng.lat && value.lng == e.latlng.lng;
                });
                //sdisplayForm(arr[0]);
                $scope.switchInfoForm();
                //console.log($scope.showInfoForm);
            }
            
            $scope.filtre = function(filtre){
                var $this = $(this);
                $this.siblings().removeClass('active');
                $this.addClass("active");
                
                $scope.map.removeLayer(layer);
                
                var temp = [];
                        
                $.each(markers,function(key,value){
                    
                     var icon = undefined;
                        switch(value.constructeur){
                            case "Maison d'aujourd'hui":
                                icon = blueIcon;
                                break;
                            case "Demeures & Cottages":
                                icon = purpleIcon;
                                break;
                            case "Maison sweet":
                                icon = orangeIcon;
                                break;
                        }
                    
                    if(value.dispo == filtre)
                        temp.push(L.marker([value.lat, value.lng], {icon : icon}).on('click', onClick));
                });
                    
                scope.layer = new L.FeatureGroup(temp).addTo(scope.map);
                
            };

        },
        link(scope,element,attrs){
            
            /*INITIALISATION DE LA MAP*/
            scope.map = L.map('map', {
                contextmenu: true,
                contextmenuWidth: 180,
                contextmenuItems: [{
                    text: 'Ajouter un marker',
                    callback: showCoordinates
                }]
            }).setView(new L.LatLng(46.5833, 0.3333), 9);
            
            /*INITIALISATION DES BOUTONS*/
            scope.toggle = L.easyButton({
              states: [{
                stateName: 'home',
                title: 'Maison finalisée',
                icon: 'glyphicon glyphicon-home',
                onClick: function(control) {
                    scope.filtre(false);
                    control.state('contstruct');
                }
              }, {
                stateName: 'contstruct',
                title: 'Maison en cours de construction',
                icon: 'glyphicon glyphicon-wrench',
                onClick: function(control) {
                    scope.filtre(true);
                    control.state('home');
                }
              }]
            });
            scope.toggle.addTo(scope.map);
            
            //Ajout du layout
            L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(scope.map);

            //Ajout de la recherche
            L.Control.geocoder({
                placeholder: "Rechercher..."
                ,errorMessage: "Aucun résultat"
            }).addTo(scope.map);
        }
    };
}]);