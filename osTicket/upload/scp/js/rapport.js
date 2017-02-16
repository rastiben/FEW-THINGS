function Rapport(){

    var self = this;

    self.addHoraires = function(ticket_id,rapport_id,agent_id,date_inter,arrive_inter,depart_inter,symptomesObservations,contrat,instal,num_affaire,callback){
        RapportAjax.addHoraires(ticket_id,rapport_id,agent_id,date_inter,arrive_inter,depart_inter,symptomesObservations,contrat,instal,num_affaire,function(){
            callback();
        });
    }

    self.updateHoraire = function(horaire_id,date_inter,arrive_inter,depart_inter,symptomesObservations,callback){
        RapportAjax.updateHoraire(horaire_id,date_inter,arrive_inter,depart_inter,symptomesObservations,function(){
            callback();
        });
    }
}

class RapportAjax{

    static doAjax(data,callback){
        $.ajax({
            url:'./Request/Rapport.php'
            ,method:'POST'
            ,data:data
        }).success(callback);
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
