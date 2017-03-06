<?php

class BDD{

    /*
    *Constante : Nom d'utilisateur de la BDD
    */
    const USERNAME = 'sa';
    /*
    *Constante : Mot de passe de la BDD
    */
    const PASSWORD = 'sage';
    /*
    *Instance de la classe BDD
    */
    private static $instance = null;
    /*
    *Instance de la base de données
    */
    private $BDD = null;


    /*
    *CONSTRUCTEUR
    */
    private function __construct(){
        try{
            $this->BDD = odbc_connect("sage", self::USERNAME, self::PASSWORD);
        }catch(PDOException $e){
            die($e);
        }
    }

    /*
    *Création de l'objet BDD;
    */
    public static function getInstance()
    {
        if(is_null(self::$instance))
        {
          self::$instance = new BDD();
        }
        return self::$instance;
    }

    /*
    *Execution d'une requete
    */
    public function execute($prepare,$values){
        return odbc_execute($prepare,$values);
    }

    public function prepare($query){
        return odbc_prepare($this->BDD,$query);
    }

}

?>
