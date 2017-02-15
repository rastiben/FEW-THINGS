function Planche() {

    /*
    *CONSTRUCTEUR
    */
    var self = this;
    AtelierAjax.getPlanches(function (data) {
        var data = $.parseJSON(data);
        //console.log(data);
        self.contenu = [];
        $(data).each(function ($number, $obj) {
            self.contenu.push(new Contenu($obj['0'],
                                          $obj['id'],
                                          $obj['planche'],
                                          ($obj['0'] == "prepa" ?
            new Preparation($obj['prepaPEC'],$obj['VD'], $obj['acrobat'], $obj['activation'], $obj['autre'], $obj['dossierSAV'],
            $obj['type'], $obj['etiquetage'], $obj['flash'], $obj['id_contenu'], $obj['java'], $obj['maj'], $obj['mdp'], $obj['modele'], $obj['pdf'], $obj['register'], $obj['septZip'], $obj['uninstall'], $obj['userAccount'], $obj['verifActivation'], $obj['divers']) :
            new Reparation($obj['repaPEC'],$obj['typeAppareil'],$obj['motDePasse'],$obj['description'],$obj['comTech'],$obj['tempsInter'],
            $obj['dateMiseADisposition'],$obj['visaClient'],$obj['visaTech'],$obj['intervention'],$obj['tempsPasse'],$obj['svisaTech'],$obj['comIntervention'],$obj['verifClient'],
            $obj['dateReprise']))));
        });


    });

    /*
    *Recupération des contenu non affectés à une planche
    */
    self.getContenues = function(callback) {
        var contenues = $.grep(self.contenu,function(obj){
            return obj.getPlanche() == null
        });
        callback(contenues);
    };

    /*
    *Recupération des contenu d'une planche
    */
    self.getPlanche = function(planche) {
        return $.grep(self.contenu,function(obj){
            return obj.getPlanche() == planche
        });
    };

    /*
    *Recupération du contenu d'un contenu d'une planche
    */
    self.getContenu = function(id){
        return $.grep(self.contenu,function(obj){
            return obj.getId() == id
        });
    };

    /*
    *Affectation d'un nouveau contenu sur une planche
    */
    self.affectContenu = function(id,planche,callback){
        AtelierAjax.affectContenu(id,planche,function(){
            var contenu =  $.grep(self.contenu,function(obj){
                return obj.getId() == id
            });

            contenu[0].planche = planche;
            callback();
        });
    };

    /*
    *Ajout d'un nouveau contenu sur une planche
    */
    self.addContenu = function(id,type){
        AtelierAjax.addContenu(id,type,function(data){
            self.contenu.push(new Contenu(type,
                                        data,
                                        (type == "prepa" ?
            new Preparation("PEC") :
            new Reparation("PEC"))));
            //callback(data);
        });
    };

    /*
    *Mise a jour ou ajout du contenu d'une prepa
    */
    self.insertOfUpdatePrepa = function(id_contenu, planche, VD, modele, etiquetage, dossierSAV, septZip, acrobat, flash, java, pdf, autre, type, userAccount, mdp, activation, uninstall, maj, register, verifActivation, divers) {
        var contenu = self.getContenu(id_contenu);
        contenu = contenu[0];
        contenu = contenu['contenu'];
        contenu.VD = VD;
        contenu.modele = modele;
        contenu.etiquetage = etiquetage;
        contenu.dossierSAV = dossierSAV;
        contenu.septZip = septZip;
        contenu.acrobat = acrobat;
        contenu.flash = flash;
        contenu.java = java;
        contenu.pdf = pdf;
        contenu.autre = autre;
        contenu.type = type;
        contenu.userAccount = userAccount;
        contenu.mdp = mdp;
        contenu.activation = activation;
        contenu.uninstall = uninstall;
        contenu.maj = maj;
        contenu.register = register;
        contenu.verifActivation = verifActivation;
        contenu.divers = divers;
        AtelierAjax.insertOrUpdatePrepa(id_contenu,VD,modele,etiquetage,dossierSAV, septZip, acrobat, flash, java, pdf, autre, type, userAccount, mdp, activation, uninstall, maj, register, verifActivation, divers);
    }

     /*
    *Mise a jour ou ajout du contenu d'une repa
    */
    self.insertOfUpdateRepa = function(id_contenu,planche,typeAppareil,motDePasse,description,comTech,tempsInter,dateMiseADisposition,visaClient,visaTech,intervention,tempsPasse,svisaTech,
    comIntervention,verifClient,dateReprise) {
        var contenu = self.getContenu(id_contenu);
        contenu = contenu[0];
        contenu = contenu['contenu'];
        contenu.typeAppareil = typeAppareil;
        contenu.motDePasse = motDePasse;
        contenu.description = description;
        contenu.comTech = comTech;
        contenu.tempsInter = tempsInter;
        contenu.dateMiseADisposition = dateMiseADisposition;
        contenu.visaClient = visaClient;
        contenu.visaTech = visaTech;
        contenu.intervention = intervention;
        contenu.tempsPasse = tempsPasse;
        contenu.svisaTech = svisaTech;
        contenu.comIntervention = comIntervention;
        contenu.verifClient = verifClient;
        contenu.dateReprise = dateReprise;
        AtelierAjax.insertOrUpdateRepa(id_contenu,planche,typeAppareil,motDePasse,description,comTech,tempsInter,dateMiseADisposition,visaClient,visaTech,intervention,tempsPasse,svisaTech,
        comIntervention,verifClient,dateReprise);
    }
}



function Contenu(type, id, planche, contenu) {

    var self = this;
    self.type = type;
    self.id = id;
    self.planche = planche;
    self.contenu = contenu;

    self.getType = function () {
        return self.type;
    }
    self.getId = function () {
        return self.id;
    }
    self.getPlanche = function () {
        return self.planche;
    }
}

function Preparation(PEC,VD=null,acrobat=null,activation=null,autre=null,dossierSAV=null,type=null,etiquetage=null,flash=null,id_contenu=null,java=null,maj=null,mdp=null,modele=null,pdf=null,register=null,septZip=null,uninstall=null,userAccount=null,verifActivation=null,divers=null){

    var self = this;
    self.PEC = PEC;
    self.VD = VD;
    self.acrobat = acrobat;
    self.activation = activation;
    self.autre = autre;
    self.dossierSAV = dossierSAV;
    self.etiquetage = etiquetage;
    self.flash = flash;
    self.id_contenu = id_contenu;
    self.java = java;
    self.maj = maj;
    self.mdp = mdp;
    self.type = type;
    self.modele = modele;
    self.pdf = pdf;
    self.register = register;
    self.septZip = septZip;
    self.uninstall = uninstall;
    self.userAccount = userAccount;
    self.verifActivation = verifActivation;
    self.divers = divers;
}

function Reparation(PEC,typeAppareil=null,motDePasse=null,description=null,comTech=null,tempsInter=null,dateMiseADisposition=null,visaClient=null,visaTech=null,intervention=null,tempsPasse=null,svisaTech=null,comIntervention=null,verifClient=null,dateReprise=null){

    var self = this;
    self.PEC = PEC;
    self.typeAppareil = typeAppareil;
    self.motDePasse = motDePasse;
    self.description = description;
    self.comTech = comTech;
    self.tempsInter = tempsInter;
    self.dateMiseADisposition = dateMiseADisposition;
    self.visaClient = visaClient;
    self.visaTech = visaTech;
    self.intervention = intervention;
    self.tempsPasse = tempsPasse;
    self.svisaTech = svisaTech;
    self.comIntervention = comIntervention;
    self.verifClient = verifClient;
    self.dateReprise = dateReprise;
}

class AtelierAjax{

    static doAjax(data,callback){
        $.ajax({
            url:'./Request/Atelier.php'
            ,method:'POST'
            ,data:data
        }).success(callback);
    }

    static getPlanches(callback){
         var data = {
                request:'getPlanches'
            };
         this.doAjax(data,callback);
    }

    static affectContenu(id,planche,callback){
         var data = {
                request:'affectContenu'
                ,id:id
                ,planche:planche
            };
         this.doAjax(data,callback);
    }

    static addContenu(id,type,callback){
        var data = {
                request:'addContenu',
                ticket_id:id,
                type:type
            };
        this.doAjax(data,callback);
    }

    static insertOrUpdatePrepa(id_contenu,vd,modele,etiquetage,dossierSAV, septZip, acrobat, flash, java, pdf, autre, type, userAccount, mdp, activation, uninstall, maj, register, verifActivation, divers){
        $.ajax({
            url:'./Request/Atelier.php'
            ,method:'POST'
            ,data : {
                request:'addPrepaInfo'
                ,id_contenu:id_contenu
                ,vd:vd
                ,modele:modele
                ,etiquetage:etiquetage
                ,dossierSAV:dossierSAV
                ,septZip:septZip
                ,acrobat:acrobat
                ,flash:flash
                ,java:java
                ,pdf:pdf
                ,autre:autre
                ,type:type
                ,userAccount:userAccount
                ,mdp: mdp
                ,activation: activation
                ,uninstall: uninstall
                ,maj: maj
                ,register: register
                ,verifActivation: verifActivation
                ,divers:divers
            }
        });
    }

    static insertOrUpdateRepa(id_contenu,planche,typeAppareil,motDePasse,description,comTech,tempsInter,dateMiseADisposition,visaClient,visaTech,intervention,tempsPasse,svisaTech,
        comIntervention,verifClient,dateReprise){
        $.ajax({
            url:'./Request/Atelier.php'
            ,method:'POST'
            ,data : {
                request:'addRepaInfo'
                ,id_contenu:id_contenu
                ,planche:planche
                ,typeAppareil:typeAppareil
                ,motDePasse:motDePasse
                ,description:description
                ,comTech:comTech
                ,tempsInter:tempsInter
                ,dateMiseADisposition:dateMiseADisposition
                ,visaClient:visaClient
                ,visaTech:visaTech
                ,intervention:intervention
                ,tempsPasse:tempsPasse
                ,svisaTech:svisaTech
                ,comIntervention:comIntervention
                ,verifClient:verifClient
                ,dateReprise:dateReprise
            }
        });
    }
}
