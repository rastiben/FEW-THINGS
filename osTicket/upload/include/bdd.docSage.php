<?php

require_once(INCLUDE_DIR . 'class.bdd.php');

class bdd_docSage{

    /*
    *Objet base de données.
    */
    private $DB = null;

    /*
    *Instance de la classe BDD
    */
    private static $instance = null;

    /*
    *Constructeur
    */
    private function __construct(){
        $this->DB = BDD::getInstance();
    }

    /*
    *Création de l'objet bdd_org;
    */
    public static function getInstance()
    {
        if(is_null(self::$instance))
        {
          self::$instance = new bdd_docSage();
        }
        return self::$instance;
    }

    public function lastError(){
        return $this->DB->lastError();
    }

    public function getLi_NO($tiers){
        $prepare = $this->DB->prepare("SELECT LI_No FROM F_LIVRAISON WHERE CT_Num = ?");
        $values = array($tiers);
        $this->DB->execute($prepare,$values);
        return $prepare;
    }

    public function getNewNumBL(){
        $prepare = $this->DB->prepare("SELECT DC_Piece FROM F_DOCCURRENTPIECE WHERE DC_Piece LIKE 'BL2N%'");
        $values = array();
        $this->DB->execute($prepare,$values);
        return $prepare;
    }

    public function createDocEntete($TIERS,$DE_NO,$LI_NO,$DO_PIECE){
        $DATE = date('\{\d Y-m-d\}');
        //$DATE = date('d-m-Y');
        $prepare = $this->DB->prepare("INSERT INTO F_DOCENTETE (CG_NUM,CT_NUMPAYEUR,DE_NO,DO_BLFACT,DO_CONDITION,DO_DOMAINE,DO_EXPEDIT,DO_NBFACTURE,DO_PERIOD,DO_PIECE,DO_REF,DO_STATUT,DO_TARIF,DO_TIERS,DO_TYPE,DO_TYPECOLIS,LI_NO,N_CATCOMPTA,DO_DATE,DO_SOUCHE) VALUES ('411000',?,?,0,1,0,1,1,1,?,'Sortie Véhicule',2,1,?,3,1,?,1,?,1)");
        $values = array($TIERS,$DE_NO,$DO_PIECE,$TIERS,$LI_NO,$DATE);
        $res = $this->DB->execute($prepare,$values);
        return $res;
    }

    public function createDocLineCMUP($org,$line,$ref,$stock,$designation,$prix){
        $prepare = $this->DB->prepare("INSERT INTO F_DOCLIGNE (DL_No,DO_Domaine,DO_Type,CT_Num,DO_Date,DO_Piece,DL_Ligne,AR_Ref,EU_Qte,DL_Valorise,DL_Qte,DE_No,PF_Num,DL_DESIGN,DL_PrixUnitaire,EU_Enumere) VALUES (0,0,3,?,'{d 2017-03-29}','BL2N97054',?,?,1,1,1,?,'',?,?,'". utf8_decode('Unité') ."')");
        $values = array($org,$line,$ref,$stock,utf8_decode($designation),$prix);
        $res = $this->DB->execute($prepare,$values);
        return $res;
    }

    public function createDocLineSN($org,$line,$ref,$stock,$designation,$prix,$sn){
        $prepare = $this->DB->prepare("INSERT INTO F_DOCLIGNE (DL_No,DO_Domaine,DO_Type,CT_Num,DO_Date,DO_Piece,DL_Ligne,AR_Ref,EU_Qte,DL_Valorise,DL_Qte,DE_No,PF_Num,DL_DESIGN,DL_PrixUnitaire,EU_Enumere,LS_NOSERIE) VALUES (0,0,3,?,'{d 2017-03-29}','BL2N97054',?,?,1,1,1,?,'',?,?,'". utf8_decode('Unité') ."',?)");
        $values = array($org,$line,$ref,$stock,utf8_decode($designation),$prix,$sn);
        $res = $this->DB->execute($prepare,$values);
        return $res;
    }
}

?>
