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

    public function addUser($org_id,$name){
        $today = date('Y-m-d H:i:s');

        /*REPLACE INTO `transcripts`
            SET `ensembl_transcript_id` = ‘ENSORGT00000000001′,
            `transcript_chrom_start` = 12345,
            `transcript_chrom_end` = 12678;*/

        $prepare = $this->prepare('INSERT INTO ost_user (org_id,name,created,updated)
                                    SELECT :org_id,:name,:created,:updated FROM DUAL
                                    WHERE NOT EXISTS (SELECT * FROM ost_user
                                          WHERE org_id=:org_id)
                                    LIMIT 1 ');
        return $this->execute($prepare,array(":org_id"=>$org_id,":name"=>$name,":created"=>$today,":updated"=>$today));
    }

    public function updateUser($org_id,$name){
        $today = date('Y-m-d H:i:s');
        $prepare = $this->prepare("UPDATE ost_user SET org_id = :org_id,name = :name,updated = :updated");
        return $this->execute($prepare,array(":org_id"=>$org_id,":name"=>$name,":updated"=>$today));
    }
}

?>
