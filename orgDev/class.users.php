<?php

require_once('bdd.user.php');
require_once('class.org.php');

class userCollection{

    /*
    *Liste des organisation
    */
    public $users = [];

    /*
    *Instance de la classe OrganisationCollection
    */
    private static $instance = null;

    /*
    *Objet base de données.
    */
    private $bdd_user = null;

    /*
    *Constructeur
    */
    private function __construct(){
        $this->bdd_user = bdd_user::getInstance();
    }

    /*
    *Création de l'objet bdd_org;
    */
    public static function getInstance()
    {
        if(is_null(self::$instance))
        {
          self::$instance = new userCollection();
        }
        return self::$instance;
    }

    /*
    *Récupération des Organisation
    */
    public function lookUp($offset=1){
        $result = $this->bdd_user->getUsers($offset);

        foreach($result as $key=>$user){
             $this->addUser($user);
        }

        return $this->getCollectionPage($offset);
    }

    /*
    *Ajout d'un élément dans la collection
    */
    private function addUser($data){
        array_push($this->users,new UserC($data));
    }

    /*
    *Récupération des occurences à afficher
    */
    public function getCollectionPage($offset=null,$query=null){
        if(empty($query)){
            return $this->users;
        } else {
            return array_splice($this->users,(50*($offset-1)),49);
        }
    }

    /*
    *Mettre à jour la base user pour créer une utilisateur lambda pour chaque organisation
    */
    public function majBaseUser(){
        //Recup org
        $orgs = OrganisationCollection::getInstance();
        $org = $orgs->lookUp();

        print_r($org[0]);

        //test de présence dans la base
        //si non création


    }

}

//CT_Num,CT_Adresse,CT_Complement,CT_CodePostal,CT_Ville,CT_Telephone,CT_Site
class UserC{

    /*
    *Données relative à l'organisation
    */
    public $data = null;

    /*
    *Constructeur
    */
    public function __construct($data){
        $this->data = array_values($data);
    }

    /*
    *Récupération de l'id
    */
    public function getId(){
        return $this->data[0];
    }

    /*
    *Récupération du nom de l'organisation
    */
    public function getName(){
        return $this->data[1];
    }

    /*
    *Récupération de l'adresse de l'organisation
    */
    public function getOrgId(){
        return $this->data[2];
    }

}
//echo $org->getName();

//echo $org->getName();
$users = userCollection::getInstance();
print_r($users->majBaseUser());
?>
