<?php

require_once(INCLUDE_DIR . 'class.bdd.php');

class bdd_org{

    /*
    *Objet base de données.
    */
    private $DB = null;

    /*
    *Constructeur
    */
    public function __construct(){
        $this->DB = BDD::getInstance();
    }

    /*
    *Récupération des organisation
    */
    public function getOrgs(){
        $prepare = $this->DB->prepare("SELECT CT_Num,CT_Adresse,CT_Complement,CT_CodePostal,CT_Ville,CT_Telephone,CT_Site FROM F_COMPTET WHERE CT_Num LIKE '%411%'");
        $values = array();
        $this->DB->execute($prepare,$values);
        return $prepare;
    }

    public function getOrgWithName($names){
        $prepare = $this->DB->prepare("SELECT CT_Num,CT_Adresse,CT_Complement,CT_CodePostal,CT_Ville,CT_Telephone,CT_Site FROM F_COMPTET WHERE CT_Num = ?");
        $values = array($names);
        $this->DB->execute($prepare,$values);
        return $prepare;
    }

}

?>
