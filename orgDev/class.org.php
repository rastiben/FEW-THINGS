<?php

require_once('bdd.org.php');

class Pagination{

    public function __construct($total,$nb){
        $this->total = $total;
        $this->nbPerPage = $nb;
        $this->nbPage = $this->total/$this->nbPerPage;
    }

    private function createPagination($page){
        $result = "";

        $uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);

        if($page > 1){
            //begin
            $result .= "<a href=\"http://".$_SERVER['HTTP_HOST'].$uri_parts[0]."?p=".(1)."\">&lt;&lt;</a>&nbsp";
            //start
            $result .= "<a href=\"http://".$_SERVER['HTTP_HOST'].$uri_parts[0]."?p=".($page-1)."\">&lt;</a>&nbsp";
        }

        $result .= "<select>";

        for($i = 1;$i<=$this->nbPage;$i++){
            $result .= "<option ". (($i == $page) ? "selected" : "") .">". $i ."</option>";
        }

        $result .= "</select>&nbsp";

        if($page < $this->nbPage){
            //after
            $result .= "<a href=\"http://".$_SERVER['HTTP_HOST'].$uri_parts[0]."?p=".($page+1)."\">&gt;</a>&nbsp";
            //end
            $result .= "<a href=\"http://".$_SERVER['HTTP_HOST'].$uri_parts[0]."?p=".($this->nbPage)."\">&gt;&gt;</a>";
        }

        return $result;

    }

    public function paginate($page=1){
        return $this->createPagination($page);
    }

}

class OrganisationCollection{

    /*
    *Liste des organisation
    */
    public $orgs = [];

    /*
    *Instance de la classe OrganisationCollection
    */
    private static $instance = null;

    /*
    *Objet base de données.
    */
    private $bdd_org = null;

    /*
    *Constructeur
    */
    private function __construct(){
        $this->bdd_org = bdd_org::getInstance();
    }

    /*
    *Création de l'objet bdd_org;
    */
    public static function getInstance()
    {
        if(is_null(self::$instance))
        {
          self::$instance = new OrganisationCollection();
        }
        return self::$instance;
    }

    /*
    *Récupération des Organisation
    */
    public function lookUp($offset=1){
        $offset -= 1;
        $result = $this->bdd_org->getOrgs();

        while($myRow = odbc_fetch_array($result)){
            array_push($this->orgs,new Organisation($myRow));
        }

        return array_splice($this->orgs,(50*$offset),50);
    }

    /*
    *Récupération des Organisation
    */
    public function lookUpByName($query,$offset){
        $offset -= 1;
        $result = $this->bdd_org->getOrgWithName($query);

        while($myRow = odbc_fetch_array($result)){
            array_push($this->orgs,new Organisation($myRow));
        }


        return array_splice($this->orgs,(50*$offset),50);
    }

    public function nbOrg(){
        return count($this->orgs);
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
$orgsC = OrganisationCollection::getInstance();

$page = isset($_REQUEST['p']) ? $_REQUEST['p'] : 1;

if(isset($_REQUEST['query'])){
    //query=toto
    $orgs = $orgsC->lookUpByName($_REQUEST['query'],$page);
    print_r($orgs);
}

?>
