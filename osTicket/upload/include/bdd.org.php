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
    *Récupération des organisation
    */
    public function getOrgs(){
        $prepare = $this->DB->prepare("SELECT CT_Num,CT_Adresse,CT_Complement,CT_CodePostal,CT_Ville,CT_Telephone,CT_Site FROM F_COMPTET WHERE CT_Num LIKE '%411%' ORDER BY CT_Num");
        $values = array();
        $this->DB->execute($prepare,$values);
        return $prepare;
    }

    public function getOrgWithName($names){
        $prepare = $this->DB->prepare("SELECT CT_Num,CT_Adresse,CT_Complement,CT_CodePostal,CT_Ville,CT_Telephone,CT_Site FROM F_COMPTET WHERE CT_Num LIKE ? ORDER BY CT_Num");
        $values = array($names."%");
        $this->DB->execute($prepare,$values);
        return $prepare;
    }

}

?>
