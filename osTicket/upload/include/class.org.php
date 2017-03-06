<?php

require_once(INCLUDE_DIR . 'bdd.org.php');

class OrganisationFactory{

    /*
    *Factory : Création avec des datas
    */
    public static function createWithData(){
        $orgs = array();
        $BD = new bdd_org();
        $result = $BD->getOrgs();

        while($myRow = odbc_fetch_array($result)){
            array_push($orgs,new Organisation($myRow));
        }

        return array_splice($orgs,0,5);
    }

    /*
    *Factory : Création à partir d'un nom
    */
    public static function createWithName($name){
        $BD = new bdd_org();
        $result = $BD->getOrgWithName($name);

        $myRow = odbc_fetch_array($result);
        $org = new Organisation($myRow);

        return $org;
    }

}

class Organisation{

    /*
    *Données relative à l'organisation
    */
    private $data = null;

    /*
    *Constructeur
    */
    public function __construct($data){
        $this->data = array_values($data);
    }

    /*
    *Récupération du nom de l'organisation
    */
    public function getName(){
        return $this->data[0];
    }
}

//$org = OrganisationFactory::createWithName("411VDOC");
//echo $org->getName();


?>
