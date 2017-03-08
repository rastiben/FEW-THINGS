<?php

require_once(INCLUDE_DIR . 'class.bdd.php');

class bdd_org{

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
          self::$instance = new bdd_org();
        }
        return self::$instance;
    }

    /*
    *Range
    */
    private function getRange($offset){
        $between = (50*($offset-1)) + 49;
        return [(50*($offset-1)),$between];
    }

    /*
    *Récupération des organisation
    */
    public function getOrgs($page){
        /*Création de la requete*/
        $fields = ["CT_Num","CT_Adresse","CT_Complement","CT_CodePostal","CT_Ville","CT_Telephone","CT_Site"];
        $whereClauses = [["CT_Num","LIKE","'%411%'"]];
        $orderBy = "CT_Num";

        $range = $this->getRange($page);

        $request = $this->DB->selectBetween("F_COMPTET",$fields,$whereClauses,$orderBy,$range);

        /*Préparation et execution de celle ci.*/
        $prepare = $this->DB->prepare($request);
        $values = array();
        $this->DB->execute($prepare,$values);
        return $prepare;
    }

    public function getOrgWithName($names,$page){
        /*Préparation et execution de celle ci.*/
        $prepare = $this->DB->prepare("SELECT CT_Num,CT_Adresse,CT_Complement,CT_CodePostal,CT_Ville,CT_Telephone,CT_Site FROM F_COMPTET WHERE CT_Num LIKE ?");
        $values = array("411".$names."%");
        $this->DB->execute($prepare,$values);
        return $prepare;
    }

    public function nbOrg($search){
        /*Préparation et execution de celle ci.*/
        $prepare = $this->DB->prepare("SELECT COUNT(*) FROM F_COMPTET WHERE CT_Num LIKE ?");
        $values = array("411".$search."%");
        $this->DB->execute($prepare,$values);
        return $prepare;
    }

}

?>
