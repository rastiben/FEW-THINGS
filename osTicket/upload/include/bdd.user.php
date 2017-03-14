<?php

class bdd_user{

    /*
    *Instance de la classe BDD
    */
    private static $instance = null;

    /*
    *Private bdd
    */
    private $bdd = null;

    /*
    *Constructeur
    */
    private function __construct(){
        try {
            $this->bdd = new PDO('mysql:host=localhost;dbname=osticket', 'root', '');
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    /*
    *Création de l'objet bdd_org;
    */
    public static function getInstance()
    {
        if(is_null(self::$instance))
        {
          self::$instance = new bdd_user();
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

    public function prepare($query){
        return $this->bdd->prepare($query);
    }

    public function execute($prepare,$values){
        $prepare->execute($values);
        return $prepare->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    *Récupération des users
    */
    public function getUsers(){
        $prepare = $this->prepare("SELECT id,name,org_id FROM ost_user");
        return $this->execute($prepare,array());
    }
}

?>
