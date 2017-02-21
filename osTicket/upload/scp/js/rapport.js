moment.locale('fr');

app.factory('rapportFactory',['$http',function($http){
   return{
       getRapports: function(ticketID) {
             //return the promise.
             return $http({method: 'POST',
                            url: './Request/Rapport.php',
                            data: $.param({request: 'getRapports',
                                           ticketID:ticketID
                                          }),
                            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                        })
                       .then(function(result) {
                            //resolve the promise as the data
                            return result.data;
                        });
        },
        getHoraires: function(rapportID) {
             //return the promise.
             return $http({method: 'POST',
                            url: './Request/Rapport.php',
                            data: $.param({request: 'getRapportsHoraires',
                                           rapportID:rapportID
                                          }),
                            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                        })
                       .then(function(result) {
                            //resolve the promise as the data
                            return result.data;
                        });
        },
       addHR: function(data){
           return $http({method: 'POST',
                            url: './Request/Rapport.php',
                            data: data,
                            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                        });
       }
   };
}]);

app.controller("rapportCtrl",["$scope","rapportFactory", function($scope,rapportFactory){
    //Init
    $scope.init = function(ticketID,staffID){
        $scope.ticketID = ticketID;
        $scope.staffID = staffID;
        //Récupération des rapports ainsi que des horaires
        rapportFactory.getRapports($scope.ticketID).then(function(rapports){
            $scope.rapports = rapports;
            $scope.rapportID = $scope.rapports[0].id;

            //Récupération des horaires pour chaque rapports.
            angular.forEach($scope.rapports,function(value,key){
                rapportFactory.getHoraires(value.id).then(function(horaires){
                    value.horaires = horaires;
                    angular.forEach(value.horaires,function(horaire,key){
                        horaire.arrive_inter = moment(horaire.arrive_inter,"YYYY/MM/DD HH:mm:ss");
                        horaire.depart_inter = moment(horaire.depart_inter,"YYYY/MM/DD HH:mm:ss");
                        var temp = moment.duration(horaire.depart_inter.diff(horaire.arrive_inter));
                        horaire.nbHours = temp._data.hours + ":" + temp._data.minutes;
                    });
                });
            });
        });
    }

    $scope.setRapportID = function($event,id,rapportID){
        $('.col-md-4.rapport').removeClass('active');
        $('#'+id+'.col-md-4.rapport').addClass('active');
        $('.rapports tbody tr').removeClass('active');
        $($event.currentTarget).addClass('active');
        $('.eachRapport.active').removeClass('active');
        $('#'+id+'.eachRapport').addClass('active');
        $scope.rapportID = rapportID;
    }

    $scope.addRapport = function(){

        //Récupération des contrat ou instal (a changer)
        if($('input[value="Contrat"]').is(':checked')){
            var i = 1;
            $.each($('.contrat.table.table-striped tbody tr:last-child td'),function(){
            if($('input',this).is(':checked')){
                $scope.contrat = $scope.contrat + i + ";";
            }
            i += 1;
        });
        } else {
            $scope.instal = 1;
        }

        var comments = $('#new_symptomesObservations').val();

        var data = $.param({request: 'addHoraires',
                        ticket_id:$scope.ticketID,
                        rapport_id:null,
                        agent_id:$scope.staffID,
                        date_inter:$scope.date_new_inter,
                        arrive_inter:$scope.arrive_new_inter,
                        depart_inter:$scope.depart_new_inter,
                        symptomesObservations:comments,
                        contrat:$scope.contrat,
                        instal:$scope.instal,
                        num_affaire:$scope.new_num_affaire
                        });
        rapportFactory.addHR(data);
        location.reload();
    }

    $scope.insertOrUpdateHoraire = function(){
        var comments = $('#symptomesObservations').val();

        if($scope.toUpdate !== undefined){
            var data = $.param({request: 'updateHoraire',
                        horaire_id:$scope.toUpdate,
                        date_inter:$scope.date_inter,
                        arrive_inter:$scope.arrive_inter,
                        depart_inter:$scope.depart_inter,
                        symptomesObservations:comments
                    });
            rapportFactory.addHR(data);
        } else {
            var data = $.param({request: 'addHoraires',
                        ticket_id:null,
                        rapport_id:$scope.rapportID,
                        agent_id:null,
                        date_inter:$scope.date_inter,
                        arrive_inter:$scope.arrive_inter,
                        depart_inter:$scope.depart_inter,
                        symptomesObservations:comments,
                        contrat:null,
                        instal:null,
                        num_affaire:null
                        });
            rapportFactory.addHR(data);
        }
        location.reload();
    }

    $scope.showUpdate = function(idR,idH,idHoraire){
        if($scope.alreadyEditingHoraire == undefined || $scope.alreadyEditingHoraire == false){
            if(idR !== undefined){
                $scope.toUpdate = idHoraire;
                    //Affectation des champs
                var horaire = $scope.rapports[idR].horaires[idH];

                $scope.date_inter = horaire.arrive_inter.format('DD/MM/YYYY');
                $scope.arrive_inter = horaire.arrive_inter.format('HH:mm');
                $scope.depart_inter = horaire.depart_inter.format('HH:mm');
                $('#addTimeDiv .redactor-editor').last().text(horaire.comment);
                $('#addTimeDiv .redactor-editor').last().attr('placeholder','');
            } else {
                $scope.toUpdate = undefined;
            }
            $('#addTimeDiv').css('display','block');
            $scope.alreadyEditingHoraire = true;
        }
    }

    $scope.unShowUpdate = function($event){
        $($event.currentTarget).parent().css('display','none');
        $scope.alreadyEditingHoraire = false;
    }

}]);

app.filter('capitalize', function() {
    return function(input) {
      return (!!input) ? input.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();}) : '';
    }
});

app.filter('mFormat', function() {
    return function(input, format) {
      return (!!input) ? input.format(format) : '';
    }
});


























function Rapports(ticketID){
    var self = this;

    RapportAjax.getRapports(ticketID,function(report){
        report = $.parseJSON(report);
        self.rapports = [];
        $(report).each(function(number,rapport){
            RapportAjax.getRapportsHoraires(rapport['id'],function(horaires){
                horaires = $.parseJSON(horaires);
                var object = new Rapport(rapport['id']
                                        ,rapport['firstname']
                                        ,rapport['lastname']
                                        ,rapport['date_rapport']
                                        ,rapport['date_inter']
                                        ,rapport['num_affaire']
                                        ,rapport['contrat']
                                        ,rapport['instal']);
                $(horaires).each(function(num,hor){
                    object.horaires.push(new horaire(hor['id']
                                                    ,hor['arrive_inter']
                                                    ,hor['depart_inter']
                                                    ,hor['comment']));
                });
                self.rapports.push(object);
            });
        });
    });

    self.addHoraires = function(ticket_id,rapport_id,agent_id,date_inter,arrive_inter,depart_inter,symptomesObservations,contrat,instal,num_affaire,callback){
        RapportAjax.addHoraires(ticket_id,rapport_id,agent_id,date_inter,arrive_inter,depart_inter,symptomesObservations,contrat,instal,num_affaire,function(){
            console.log(rapport_id);
            callback();
        });
    }

    self.getHoraire = function(id){
        var horaires = [];
        $(self.rapports).each(function(number,rapp){
            $(rapp.horaires).each(function(number,hor){
                if(hor.id == id) horaires.push(hor);
            });
        });
        return horaires[0];
    }

    self.updateHoraire = function(horaire_id,date_inter,arrive_inter,depart_inter,symptomesObservations,callback){
        RapportAjax.updateHoraire(horaire_id,date_inter,arrive_inter,depart_inter,symptomesObservations,function(){
            callback();
        });
    }
}


function Rapport(id,firstname,lastname,date_rapport,date_inter,num_affaire,contrat,instal){
    var self = this;
    self.id = id;
    self.firstname = firstname;
    self.lastname = lastname;
    self.date_rapport = date_rapport;
    self.date_inter = date_inter;
    self.num_affaire = num_affaire;
    self.contrat = contrat;
    self.instal = instal;
    self.horaires = [];
}

function horaire(id,arrive_inter,depart_inter,comment){

    var self = this;
    self.id = id;
    self.arrive_inter = arrive_inter;
    self.depart_inter = depart_inter;
    self.comment = comment;
}

class RapportAjax{

    static doAjax(data,callback){
        $.ajax({
            url:'./Request/Rapport.php'
            ,method:'POST'
            ,data:data
        }).success(callback);
    }

    static getRapports(ticketID,callback){
        var data = {
            request:'getRapports'
            ,ticketID:ticketID
        };
        this.doAjax(data,callback);
    }

    static getRapportsHoraires(rapportID,callback){
        var data = {
            request:'getRapportsHoraires'
            ,rapportID:rapportID
        };
        this.doAjax(data,callback);
    }

    static addHoraires(ticket_id,rapport_id,agent_id,date_inter,arrive_inter,depart_inter,symptomesObservations,contrat,instal,num_affaire,callback){
         var data = {
            request:'addHoraires'
             ,ticket_id:ticket_id
             ,rapport_id:rapport_id
             ,agent_id:agent_id
             ,date_inter:date_inter
             ,arrive_inter:arrive_inter
             ,depart_inter:depart_inter
             ,symptomesObservations:symptomesObservations
             ,contrat:contrat
             ,instal:instal
             ,num_affaire:num_affaire
            };
         this.doAjax(data,callback);
    }

    static updateHoraire(horaire_id,date_inter,arrive_inter,depart_inter,symptomesObservations,callback){
         var data = {
            request:'updateHoraire'
            ,horaire_id
            ,date_inter
            ,arrive_inter
            ,depart_inter
            ,symptomesObservations
        };
        this.doAjax(data,callback);
    }
}
