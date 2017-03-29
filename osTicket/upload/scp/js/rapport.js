moment.locale('fr');

/*CONFIG*/
var serialize = function(obj, prefix) {
  var str = [];
  for(var p in obj) {
    if (obj.hasOwnProperty(p)) {
      var k = prefix ? prefix + "[" + p + "]" : p, v = obj[p];
      str.push(typeof v == "object" ?
        serialize(v, k) :
        encodeURIComponent(k) + "=" + encodeURIComponent(v));
    }
  }
  return str.join("&");
};

app.factory('stockFactory',['$http','$rootScope','$httpParamSerializerJQLike',function($http,$rootScope,$httpParamSerializerJQLike){
    var stock;
    return {
        query : function(agent) {
             return $http({method: 'POST',
                    url: './ajaxs.php/stock/'+agent,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                })
                .then(function(data){
                    stock = data;
                })
         },
         getSN : function(reference,agent){
             return $http({method: 'POST',
                    url: './ajaxs.php/stock/sn/'+reference+'/'+agent,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                });
         },
         createDocEntete : function(org,stock){
             return $http({method: 'POST',
                    url: './ajaxs.php/docSage/entete/'+org+'/'+stock,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                });
         },
         createDocLines : function(org,stock,lines){
             return $http({method: 'POST',
                    url: './ajaxs.php/docSage/lines',
                    data: {org:org,
                           stock:stock,
                           lines:lines},
                    headers: {'Content-Type': 'application/json'}
                });
         },
         getStock: function() {
             return stock;
         }
    }
}]);


//récupération des informations (Rapports et horaires) + Ajout d'un rapport ou maj d'un horaires
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

app.controller("rapportCtrl",["$scope","rapportFactory","stockFactory","$rootScope", function($scope,rapportFactory,stockFactory,$rootScope){
    //Init
    $scope.init = function(ticketID,staffID,TopicID){
        $scope.ticketID = ticketID;
        $scope.staffID = staffID;
        $scope.TopicID = TopicID;
        //Récupération des rapports ainsi que des horaires
        rapportFactory.getRapports($scope.ticketID).then(function(rapports){
            if(rapports.length > 0){
            $scope.rapports = rapports;
            $scope.rapportID = $scope.rapports[0].id;

            //Récupération des horaires pour chaque rapports.
            angular.forEach($scope.rapports,function(value,key){
                rapportFactory.getHoraires(value.id).then(function(horaires){
                    value.horaires = horaires;
                    value.totalHours = moment.duration(0,'h');
                    angular.forEach(value.horaires,function(horaire,key){
                        horaire.arrive_inter = moment(horaire.arrive_inter,"YYYY/MM/DD HH:mm:ss");
                        horaire.depart_inter = moment(horaire.depart_inter,"YYYY/MM/DD HH:mm:ss");

                        var temp = moment.duration(horaire.depart_inter.diff(horaire.arrive_inter));
                        horaire.nbHours = temp._data.hours + ":" + temp._data.minutes;

                        //temps total sur un rapport
                        value.totalHours.add(temp._data.hours,'h');
                        value.totalHours.add(temp._data.minutes,'m');
                    });
                });
            });
            }
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

    $scope.tempsPasse = function(duration){
        var totalHours = duration.as('seconds');
        var days = Math.floor(totalHours / 27900);
        if(days >= 1){
            totalHours = totalHours - (days * 27900);
        }
        var hours = Math.floor(totalHours / 3600);
        if(hours >= 1){
            totalHours = totalHours - (hours * 3600);
        }
        var minutes = Math.floor(totalHours / 60);
        return "<p>" + days + "  Jours</p> \
                <p>" + hours + "  Heures & " + minutes + " Minutes</p>";
    }

    $scope.addRapport = function(){

        //Récupération des contrat ou instal (a changer)
        if($('input[value="Contrat"]').is(':checked')){
            var i = 1;
            $scope.contrat = "";
            $.each($('.contrat.table.table-striped tbody tr:last-child td'),function(index, element){
            if($('input',element).is(':checked')){
                $scope.contrat += $scope.contrat + i + ";";
            }
            i += 1;
            $scope.instal = 0;
        });
        } else {
            $scope.instal = 1;
            $scope.contrat = null;
        }

        var comments = $('#new_symptomesObservations').val();

        var data = $.param({request: 'addHoraires',
                        ticket_id:$scope.ticketID,
                        rapport_id:null,
                        topic_id:$scope.TopicID,
                        agent_id:$scope.staffID,
                        date_inter:$scope.date_new_inter,
                        arrive_inter:$scope.arrive_new_inter,
                        depart_inter:$scope.depart_new_inter,
                        symptomesObservations:comments,
                        contrat:$scope.contrat,
                        instal:$scope.instal
                        });
        rapportFactory.addHR(data);
        location.reload();
        //window.location = window.location.href;
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
                        instal:null
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

    /*STOCK*/
    $scope.getStock = function(agent){
        $scope.agent = agent;
        //display balls
        $('.ballss').css('display','block');
        $('.fixed-right').css('display','none');
        $('.ticket_right').css('height','auto');

        stockFactory.query('nicolas').then(function(){
            $scope.stock = stockFactory.getStock().data;
            $rootScope.$broadcast('STOCK', $scope.stock);
            $('.ballss').css('display','none');
            $('.stock').css('display','block');


            //creation de l'entete du BL
            /*stockFactory.createDocEntete(org,$scope.stock[0].stock).then(function(){
                //DO SOMETHING
            });*/
        });
        /*stockFactory.getStock('nicolas').then(function(stock){
            $('.ballss').css('display','none');
            $('.stock').css('display','block');
            //console.log($scope.stock);
        });*/
    }

}]);

//filtre de capitalization.
app.filter('capitalize', function() {
    return function(input) {
      return (!!input) ? input.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();}) : '';
    }
});

//filtre de formatage de moment date
app.filter('mFormat', function() {
    return function(input, format) {
      return (!!input) ? input.format(format) : '';
    }
});

app.filter('pastTimes', function() {
    return function(input, $scope) {
      return (!!input) ? $scope.tempsPasse(input) : '';
    }
});

app.controller("stockCtrl",["$scope","stockFactory","$rootScope","$compile", function($scope,stockFactory,$rootScope,$compile){
    $scope.$on('STOCK', function(response,stock) {
        $scope.stock = stock;
        $scope.stockOut = JSON.parse(JSON.stringify($scope.stock));
        $.each($scope.stockOut,function(key,value){
             value.quantite = 0;
        });
    })

    $scope.manageStock = function(reference,dir,max,$event){
        //VARIABLES
        var obj = $scope.stockOut.filter(function(e) { return e.reference == reference; })[0];
        var button = $event.currentTarget;

        if (dir == 'up') {
            if (obj.quantite < max) {
                //TEST SI L'ARTICLE EST SERIALISE.
                $scope.serialClicked = reference;
                if (obj.suiviStock == 1){
                    //Récupération des numero de serie lié à cet article.
                    var tr = $(button).closest('tr');
                    tr.children().hide();
                    $('.ballss').css('display','block');
                    stockFactory.getSN(reference,'NICOLAS').then(function(SN){
                        var serialNumbers = SN.data;

                        //affichage du numero de serie
                        $('.ballss').css('display','none');
                        tr.append('<td><select></select></td>');
                        var select = tr.find('select');
                        $.each(serialNumbers,function(key,value){
                            select.append('<option>'+value+'</option>');
                        });
                        tr.append($compile('<td><button class="btn btn-sucess" ng-click="validSN($event)">Valider</button></td>')($scope));
                        //$compile(tr)($scope);
                    });
                } else {
                   obj.quantite += 1;
                }
                //Augmentation de la quantité souhaité.
                //obj.quantite += 1;
            }
        } else {
            if (obj.quantite > 0) {
                obj.quantite -= 1;
            }
        }

    }

    $scope.validSN = function($event){
        var obj = $scope.stockOut.filter(function(e) { return e.reference == $scope.serialClicked; })[0];
        obj.quantite += 1;
        //get selected serial number
        var button = $event.currentTarget;
        var sn = $(button).parent().prev().children('select').val();

        //ajout du serial number
        obj.sn.push(sn);

        //MAJ VISUEL
        var tr = $(button).closest('tr');
        tr.children().slice(-2).remove();
        tr.children().show();
    }

    $scope.createLines = function(org){
        var obj = $scope.stockOut.filter(function(e) { return e.quantite > 0});
        //console.log(obj);
        stockFactory.createDocLines(org,$scope.stock[0].stock,JSON.stringify(obj)).then(function(){

        });
    }

}]);




































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
