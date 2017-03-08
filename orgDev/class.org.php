<?php

require_once('bdd.org.php');

class Pagination{

    public function __construct($total,$nb){
        $this->total = $total;
        $this->nbPerPage = $nb;
        $this->nbPage = ceil($this->total/$this->nbPerPage);
    }

    private function createPagination($page){
        $result = "";

        $uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);

        if(isset($_REQUEST['query'])){
            $query = "&query=" . $_REQUEST['query'];
        }

        if($page > 1){
            //begin
            $result .= "<a href=\"http://".$_SERVER['HTTP_HOST'].$uri_parts[0]."?p=".(1).$query."\">&lt;&lt;</a>&nbsp";
            //start
            $result .= "<a href=\"http://".$_SERVER['HTTP_HOST'].$uri_parts[0]."?p=".($page-1).$query."\">&lt;</a>&nbsp";
        }

        $result .= "<select onchange=\"location = this.value;\">";

        for($i = 1;$i<=$this->nbPage;$i++){
            $result .= "<option ". (($i == $page) ? "selected" : "") ." value=\"". "http://".$_SERVER['HTTP_HOST'].$uri_parts[0]."?p=".($i).$query."\" >". $i ."</option>";
        }

        $result .= "</select>&nbsp";

        if($page < $this->nbPage){
            //after
            $result .= "<a href=\"http://".$_SERVER['HTTP_HOST'].$uri_parts[0]."?p=".($page+1).$query."\">&gt;</a>&nbsp";
            //end
            $result .= "<a href=\"http://".$_SERVER['HTTP_HOST'].$uri_parts[0]."?p=".($this->nbPage).$query."\">&gt;&gt;</a>";
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
        $result = $this->bdd_org->getOrgs($offset);

        while($myRow = odbc_fetch_array($result)){
            $this->addOrg($myRow);
        }

        return $this->getCollectionPage($offset);
    }

    /*
    *Récupération des Organisation
    */
    public function lookUpByName($query,$offset=1){
        $result = $this->bdd_org->getOrgWithName($query,$offset);

        while($myRow = odbc_fetch_array($result)){
            $this->addOrg($myRow);
        }

        return $this->getCollectionPage($offset,$query);
    }

    /*
    *Ajout d'un élément dans la collection
    */
    private function addOrg($data){
        array_push($this->orgs,new Organisation($data));
    }

    /*
    *Récupération des occurences à afficher
    */
    public function getCollectionPage($offset,$query=null){
        if(empty($query)){
            return $this->orgs;
        } else {
            return array_splice($this->orgs,(50*($offset-1)),49);
        }
    }

    /*
    *Retourne le nombre d'organisation retounée
    */
    public function nbOrg($search){
        return array_values(odbc_fetch_array($this->bdd_org->nbOrg($search)))[0];
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
$orgs = $orgsC->lookUp($_REQUEST['p']);
print_r($orgs);

?>
