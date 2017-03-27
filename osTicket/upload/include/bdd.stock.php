<?php

require_once(INCLUDE_DIR . 'class.bdd.php');

class bdd_stock{

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
          self::$instance = new bdd_stock();
        }
        return self::$instance;
    }

    public function getStock($stock){
        $prepare = $this->DB->prepare("SELECT AR_Ref,AS_QteSto FROM F_ARTSTOCK,F_DEPOT WHERE  F_DEPOT.DE_No = F_ARTSTOCK.DE_No AND F_DEPOT.DE_Intitule = ? AND F_ARTSTOCK.AS_QteSto > 0");
        $values = array($stock);
        $this->DB->execute($prepare,$values);
        return $prepare;
    }

}

?>
