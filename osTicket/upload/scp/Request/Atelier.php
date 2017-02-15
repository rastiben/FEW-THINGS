<?php

class Atelier
{
    private static $instance;
    private $hostname;
    private $username;
    private $password;
    private $dbh;

    private function __construct()
    {
    // Your "heavy" initialization stuff here
        $this->hostname = 'localhost';
        $this->username = 'root';
        $this->password = '';
        try{
            $this->dbh = new PDO('mysql:host=localhost;dbname=osticket', $this->username, $this->password);
        }catch(PDOException $e){
            die($e);
        }
    }

    public static function getInstance()
    {
        if ( is_null( self::$instance ) )
        {
        self::$instance = new self();
        }
        return self::$instance;
    }

    public function add_contenu($ticket_id,$type){
        $res = $this->dbh->prepare("INSERT INTO ost_atelier_planche_contenu (ticket_id,type_id)
        VALUES (:ticket_id,
        (SELECT id FROM ost_atelier_contenu_type WHERE type = :type))");
        $res->execute(array(':ticket_id'=>$ticket_id,':type'=>$type));
        //return $res->fetchAll();
        return $this->dbh->lastInsertId();
    }

    public function get_org_planches(){
        $res = $this->dbh->prepare("SELECT ost_organization.name,ost_atelier_planche_contenu.planche_id
        FROM ost_user,ost_organization,ost_user__cdata,ost_ticket,ost_atelier_planche,ost_atelier_planche_contenu,ost_atelier_contenu_type
        WHERE ost_user.org_id = ost_organization.id
        AND ost_user.id = ost_user__cdata.user_id
        AND ost_ticket.user_id = ost_user.id
        AND ost_ticket.ticket_id = ost_atelier_planche_contenu.ticket_id
        AND ost_atelier_planche.id = ost_atelier_planche_contenu.planche_id
        AND ost_atelier_contenu_type.id = ost_atelier_planche_contenu.type_id");
        $res->execute();
        return $res->fetchAll();
    }

    public function addPrepaInfo($id_contenu,$VD,$modele,$etiquetage,$dossierSAV, $septZip, $acrobat, $flash, $java, $pdf, $autre, $type, $userAccount, $mdp, $activation, $uninstall, $maj, $register, $verifActivation, $divers){
        $res = $this->dbh->prepare("INSERT INTO ost_atelier_preparation (id_contenu, VD, modele, etiquetage, dossierSAV, septZip, acrobat, flash, java, pdf, autre, type, userAccount, mdp, activation, uninstall, maj, register, verifActivation, divers)
        VALUES(:id_contenu,:vd,:modele,:etiquetage,:dossierSAV, :septZip, :acrobat, :flash, :java, :pdf, :autre, :type, :userAccount, :mdp, :activation, :uninstall, :maj, :register, :verifActivation, :divers)
        ON DUPLICATE KEY UPDATE
        VD = :vd,
        modele = :modele,
        etiquetage = :etiquetage,
        dossierSAV = :dossierSAV,
        septZip = :septZip,
        acrobat = :acrobat,
        flash = :flash,
        java = :java,
        pdf = :pdf,
        autre = :autre,
        type = :type,
        userAccount = :userAccount,
        mdp = :mdp,
        activation = :activation,
        uninstall = :uninstall,
        maj = :maj,
        register = :register,
        verifActivation = :verifActivation,
        divers = :divers");
        $res->execute(array(':id_contenu'=>$id_contenu,
                            ':vd'=>$VD,
                            ':modele'=>$modele,
                            ':etiquetage'=>$etiquetage,
                            ':dossierSAV'=>$dossierSAV,
                            ':septZip'=>$septZip,
                            ':acrobat'=>$acrobat,
                            ':flash'=>$flash,
                            ':java'=>$java,
                            ':pdf'=>$pdf,
                            ':autre'=>$autre,
                            ':type'=>$type,
                            ':userAccount'=>$userAccount,
                            ':mdp'=>$mdp,
                            ':activation'=>$activation,
                            ':uninstall'=>$uninstall,
                            ':maj'=>$maj,
                            ':register'=>$register,
                            ':verifActivation'=>$verifActivation,
                            ':divers'=>$divers));
        return $res->fetchAll();
    }

    public function addRepaInfo($id_contenu,$typeAppareil,$motDePasse,$description,$comTech,$tempsInter,$dateMiseADisposition,$visaClient,$visaTech,$intervention,$tempsPasse,$svisaTech,
    $comIntervention,$verifClient,$dateReprise){
        $res = $this->dbh->prepare("INSERT INTO ost_atelier_reparation(id_contenu,typeAppareil,motDePasse,description,comTech,tempsInter,dateMiseADisposition,visaClient,visaTech,intervention,
        tempsPasse,svisaTech,comIntervention,verifClient,dateReprise)
        VALUES(:id_contenu,:typeAppareil,:motDePasse,:description,:comTech,:tempsInter,:dateMiseADisposition,:visaClient,:visaTech,:intervention,:tempsPasse,:svisaTech,
        :comIntervention,:verifClient,:dateReprise)
        ON DUPLICATE KEY UPDATE
        typeAppareil = :typeAppareil,
        motDePasse = :motDePasse,
        description = :description,
        comTech = :comTech,
        tempsInter = :tempsInter,
        dateMiseADisposition = :dateMiseADisposition,
        visaClient = :visaClient,
        visaTech = :visaTech,
        intervention = :intervention,
        tempsPasse = :tempsPasse,
        svisaTech = :svisaTech,
        comIntervention = :comIntervention,
        verifClient = :verifClient,
        dateReprise = :dateReprise");
        $res->execute(array(':id_contenu'=>$id_contenu,
                            ':typeAppareil'=>$typeAppareil,
                            ':motDePasse'=>$motDePasse,
                            ':description'=>$description,
                            ':comTech'=>$comTech,
                            ':tempsInter'=>$tempsInter,
                            ':dateMiseADisposition'=>$dateMiseADisposition,
                            ':visaClient'=>$visaClient,
                            ':visaTech'=>$visaTech,
                            ':intervention'=>$intervention,
                            ':tempsPasse'=>$tempsPasse,
                            ':svisaTech'=>$svisaTech,
                            ':comIntervention'=>$comIntervention,
                            ':verifClient'=>$verifClient,
                            ':dateReprise'=>$dateReprise));
        return $res->fetchAll();
    }

    public function getPlanches(){
        $res = $this->dbh->prepare("SELECT ost_atelier_contenu_type.type,ost_atelier_planche.planche,ost_atelier_planche_contenu.id,ifnull(ost_atelier_preparation.id_contenu,'PEC') as prepaPEC, ost_atelier_preparation.*,ifnull(ost_atelier_reparation.id_contenu,'PEC') as repaPEC,ost_atelier_reparation.*
        FROM ost_atelier_planche_contenu
        INNER JOIN ost_atelier_contenu_type
        ON ost_atelier_contenu_type.id = ost_atelier_planche_contenu.type_id
        LEFT JOIN ost_atelier_planche
        ON ost_atelier_planche.id = ost_atelier_planche_contenu.planche_id
        LEFT JOIN ost_atelier_preparation
        ON ost_atelier_planche_contenu.id = ost_atelier_preparation.id_contenu
        LEFT JOIN ost_atelier_reparation
        ON ost_atelier_planche_contenu.id = ost_atelier_reparation.id_contenu");
        $res->execute(array());
        return $res->fetchAll();
    }

    public function affectContenu($id,$planche){
        $res = $this->dbh->prepare("UPDATE ost_atelier_planche_contenu
        SET planche_id = (SELECT id FROM ost_atelier_planche WHERE planche = :planche)
        WHERE id = :id");
        $res->execute(array(':id'=>$id,':planche'=>$planche));
    }

    //43364101
}

if(isset($_REQUEST['request'])){
    if($_REQUEST['request'] == 'addContenu'){
        echo Atelier::getInstance()->add_contenu($_REQUEST['ticket_id'],$_REQUEST['type']);
    } else if($_REQUEST['request'] == 'addPrepaInfo'){
        Atelier::getInstance()->addPrepaInfo($_REQUEST['id_contenu'],
                                            $_REQUEST['vd'],
                                            $_REQUEST['modele'],
                                            $_REQUEST['etiquetage'],
                                            $_REQUEST['dossierSAV'],
                                            $_REQUEST['septZip'],
                                            $_REQUEST['acrobat'],
                                            $_REQUEST['flash'],
                                            $_REQUEST['java'],
                                            $_REQUEST['pdf'],
                                            $_REQUEST['autre'],
                                            $_REQUEST['type'],
                                            $_REQUEST['userAccount'],
                                            $_REQUEST['mdp'],
                                            $_REQUEST['activation'],
                                            $_REQUEST['uninstall'],
                                            $_REQUEST['maj'],
                                            $_REQUEST['register'],
                                            $_REQUEST['verifActivation'],
                                            $_REQUEST['divers']);
    } else if($_REQUEST['request'] == 'addRepaInfo'){
        Atelier::getInstance()->addRepaInfo($_REQUEST['id_contenu'],
                                             $_REQUEST['typeAppareil'],
                                             $_REQUEST['motDePasse'],
                                             $_REQUEST['description'],
                                             $_REQUEST['comTech'],
                                             $_REQUEST['tempsInter'],
                                             $_REQUEST['dateMiseADisposition'],
                                             $_REQUEST['visaClient'],
                                             $_REQUEST['visaTech'],
                                             $_REQUEST['intervention'],
                                             $_REQUEST['tempsPasse'],
                                             $_REQUEST['svisaTech'],
                                             $_REQUEST['comIntervention'],
                                             $_REQUEST['verifClient'],
                                             $_REQUEST['dateReprise']);
    } else if($_REQUEST['request'] == 'getPlanches'){
        echo json_encode(Atelier::getInstance()->getPlanches());
    } else if($_REQUEST['request'] == 'affectContenu'){
        Atelier::getInstance()->affectContenu($_REQUEST['id'],$_REQUEST['planche']);
    }
}

?>
