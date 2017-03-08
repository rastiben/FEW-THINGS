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

    /*
    *Préparation d'une requete
    */
    public function prepare($query){
        return odbc_prepare($this->BDD,$query);
    }

    /*
    *Création d'une requete select
    */
    public function selectBetween($table,$fields,$clauses="",$orderBy="",$range){
                print_r($range);
        $listField = "";

        foreach($fields as $key=>$field){
            $listField .= $field . ",";
            /*if(count($fields) > ($key+1))
                $listField .= ",";*/
        }

        $listClause = "";
        foreach($clauses as $key=>$clause){
            foreach($clause as $def){
                $listClause .= $def . " ";
            }
            if(count($clauses) > ($key+1))
                $listClause .= "AND ";
        }

        return "WITH OrderedOrg AS (SELECT ". $listField ."ROW_NUMBER() OVER (ORDER BY ". $orderBy .") AS 'RowNumber' FROM ". $table ." WHERE ". $listClause .") SELECT * FROM OrderedOrg WHERE RowNumber BETWEEN ".$range[0]." AND ".$range[1].";";

        //return "SELECT " . $listField . " FROM " . $table . " WHERE " . $listClause . " ORDER BY " . $orderBy;
    }

}

?>
