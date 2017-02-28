//moment.locale('fr');

//récupération des informations (Rapports et horaires) + Ajout d'un rapport ou maj d'un horaires
app.factory('atelierFactory',['$http',function($http){
   return{
       getAtelier: function(ticketID) {
             //return the promise.
             return $http({method: 'POST',
                            url: './Request/Atelier.php',
                            data: $.param({request: 'getAtelierTicket',
                                           ticketID:ticketID
                                          }),
                            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                        })
                       .then(function(result) {
                            //resolve the promise as the data
                            return result.data;
                        });
        }
   };
}]);

app.controller("atelierCtrl",["$scope","atelierFactory", function($scope,atelierFactory){
    //Init
    $scope.init = function(ticketID){
        $scope.ticketID = ticketID;
        atelierFactory.getAtelier($scope.ticketID).then(function(atelier){
            $scope.atelier = atelier;
        });
    }

    $scope.displayCard = function(){

        var element = $('#newFicheSuivi');

        var css = {};
        $scope.degD = 0;
        $scope.degF = 0;

        if(element.css('height') != "54px"){
            css = { height: "54px"};
            $scope.degD = 45;
            $scope.degF = 0;
        }
        else{
            //GET AUTO HEIGHT
            var curHeight = element.height(),
            autoHeight = element.css('height', 'auto').height();
            element.height(curHeight);

            css = { height: autoHeight};
            $scope.degD = 0;
            $scope.degF = 45;
        }

        element.animate(css,600);

        var elem = $('span',element);
        //ANIMATE PLUS
        $({deg: $scope.degD}).animate({deg: $scope.degF}, {
            duration: 450,
            step: function(now){
                elem.css({
                        transform: "rotate(" + now + "deg)"
                });
            }
        });
    }

}]);
