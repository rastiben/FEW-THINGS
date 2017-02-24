function Planche() {

    /*
    *CONSTRUCTEUR
    */
    var self = this;
    AtelierAjax.getPlanches(function (data) {
        var data = $.parseJSON(data);
        console.log(data);
        self.contenu = [];
        $(data).each(function ($number, $obj) {
            self.contenu.push(new Contenu($obj['contenuType'],
                                          $obj['numContenue'],
                                          $obj['planche'],
                                          $obj['etat'],
                                          ($obj['contenuType'] == "prepa" ?
            new Preparation(new VD($obj['id'],$obj['client'],$obj['type'],$obj['numeroSerie'],$obj['versionWindows'],$obj['numLicenceW'],$obj['versionOffice'],$obj['numLicenceO'],$obj['garantie'],$obj['debutGarantie'],$obj['mail'],$obj['mdp']), $obj['acrobat'], $obj['activation'], $obj['autre'], $obj['dossierSAV'],$obj['type'], $obj['etiquetage'], $obj['flash'], $obj['id_contenu'], $obj['java'], $obj['maj'], $obj['mdp'], $obj['modele'], $obj['pdf'], $obj['register'], $obj['septZip'], $obj['uninstall'], $obj['userAccount'], $obj['verifActivation'], $obj['divers']) :
            new Reparation($obj['typeAppareil'],$obj['motDePasse'],$obj['description'],$obj['comTech'],$obj['tempsInter'],$obj['dateMiseADisposition'],$obj['visaClient'],$obj['visaTech'],$obj['intervention'],$obj['tempsPasse'],$obj['svisaTech'],$obj['comIntervention'],$obj['verifClient'],
            $obj['dateReprise']))));
        });


    });

    /*
    *Recupération des contenu non affectés à une planche
    */
    self.getContenues = function(callback) {
        var contenues = $.grep(self.contenu,function(obj){
            return (obj.getPlanche() == null && obj.getEtat() != "Terminé")
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
            var contenu =  self.getContenu(id);

            contenu[0].planche = planche;
            callback(contenu[0]);
        });
    };

    self.changeState = function(id,state){
        var contenu =  self.getContenu(id);
        contenu[0].etat = state;

        AtelierAjax.changeState(id,state,function(){
        });
    }

    /*
    *Ajout d'un nouveau contenu sur une planche
    */
    self.addContenu = function(id,type,planche=null){
        var etat = (type == "prepa" ? "Planche" : "Entrées");
        AtelierAjax.addContenu(id,type,planche,etat,function(data){
            self.contenu.push(new Contenu(type,
                                        data,
                                        planche,
                                        etat,
                                        (type == "prepa" ?
            new Preparation() :
            new Reparation())));
            //callback(data);
        });
    };

    /*
    *Mise a jour ou ajout du contenu d'une prepa
    */
    self.insertOfUpdatePrepa = function(id_contenu, planche, modele, etiquetage, dossierSAV, septZip, acrobat, flash, java, pdf, autre, type, userAccount, mdp, activation, uninstall, maj, register, verifActivation, divers, client, type, numeroSerie, versionWindows, numLicenceW, versionOffice, numLicenceO, garantie, debutGarantie, mail, mdpMail) {
        var contenu = self.getContenu(id_contenu);
        contenu = contenu[0];
        contenu = contenu['contenu'];
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

        //MAJ VD
        contenu.VD.client = client;
        contenu.VD.type = type;
        contenu.VD.numeroSerie = numeroSerie;
        contenu.VD.versionWindows = versionWindows;
        contenu.VD.numLicenceW = numLicenceW;
        contenu.VD.versionOffice = versionOffice;
        contenu.VD.numLicenceO = numLicenceO;
        contenu.VD.garantie = garantie;
        contenu.VD.debutGarantie = debutGarantie;
        contenu.VD.mail = mail;
        contenu.VD.mdp = mdpMail;

        AtelierAjax.insertOrUpdatePrepa(id_contenu,modele,etiquetage,dossierSAV, septZip, acrobat, flash, java, pdf, autre, type, userAccount, mdp, activation, uninstall, maj, register, verifActivation, divers);
        AtelierAjax.updateVD(contenu.VD.id,client,type,numeroSerie,versionWindows,numLicenceW,versionOffice,numLicenceO,garantie,debutGarantie,mail,mdpMail);
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



function Contenu(type, id, planche, etat, contenu) {

    var self = this;
    self.type = type;
    self.id = id;
    self.planche = planche;
    self.contenu = contenu;
    self.etat = etat;

    self.getType = function () {
        return self.type;
    }
    self.getId = function () {
        return self.id;
    }
    self.getPlanche = function () {
        return self.planche;
    }
    self.getEtat = function () {
        return self.etat;
    }
}

function Preparation(VD=null,acrobat=null,activation=null,autre=null,dossierSAV=null,type=null,etiquetage=null,flash=null,id_contenu=null,java=null,maj=null,mdp=null,modele=null,pdf=null,register=null,septZip=null,uninstall=null,userAccount=null,verifActivation=null,divers=null){

    var self = this;
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

function Reparation(typeAppareil=null,motDePasse=null,description=null,comTech=null,tempsInter=null,dateMiseADisposition=null,visaClient=null,visaTech=null,intervention=null,tempsPasse=null,svisaTech=null,comIntervention=null,verifClient=null,dateReprise=null){

    var self = this;
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

function VD(id,client,type,numeroSerie,versionWindows,numLicenceW,versionOffice,numLicenceO,garantie,debutGarantie,mail,mdp){

    var self = this;

    self.id = id;
    self.client = client;
    self.type = type;
    self.numeroSerie = numeroSerie;
    self.versionWindows = versionWindows;
    self.numLicenceW = numLicenceW;
    self.versionOffice = versionOffice;
    self.numLicenceO = numLicenceO;
    self.garantie = garantie;
    self.debutGarantie = debutGarantie;
    self.mail = mail;
    self.mdp = mdp;

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

    static changeState(id,state,callback){
         var data = {
                request:'changeState'
                ,id:id
                ,etat:state
            };
         this.doAjax(data,callback);
    }

    static addContenu(id,type,planche,etat,callback){
        var data = {
                request:'addContenu',
                ticket_id:id,
                type:type,
                planche:planche,
                etat:etat
            };
        this.doAjax(data,callback);
    }

    static insertOrUpdatePrepa(id_contenu,modele,etiquetage,dossierSAV, septZip, acrobat, flash, java, pdf, autre, type, userAccount, mdp, activation, uninstall, maj, register, verifActivation, divers){
        $.ajax({
            url:'./Request/Atelier.php'
            ,method:'POST'
            ,data : {
                request:'addPrepaInfo'
                ,id_contenu:id_contenu
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

    static updateVD(id,client,type,numeroSerie,versionWindows,numLicenceW,versionOffice,numLicenceO,garantie,debutGarantie,mail,mdp){
        $.ajax({
            url:'./Request/Atelier.php'
            ,method:'POST'
            ,data : {
                request:'updateVD'
                ,id:id
                ,client:client
                ,type:type
                ,numeroSerie:numeroSerie
                ,versionWindows:versionWindows
                ,numLicenceW:numLicenceW
                ,versionOffice:versionOffice
                ,numLicenceO:numLicenceO
                ,garantie:garantie
                ,debutGarantie:debutGarantie
                ,mail:mail
                ,mdp:mdp
            }
        });
    }

}
