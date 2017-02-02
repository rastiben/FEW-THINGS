<?php

class Organisation
{
    private static $instance;
    private $hostname;
    private $username;
    private $password;
    private $dbh;

    private function __construct()
    {
    // Your "heavy" initialization stuff here
        $this->hostname = 'localhost';
        $this->username = 'root';
        $this->password = '';
        try{
            $this->dbh = new PDO('mysql:host=localhost;dbname=osticket', $this->username, $this->password);
        }catch(PDOException $e){
            die($e);
        }
    }

    public static function getInstance()
    {
        if ( is_null( self::$instance ) )
        {
        self::$instance = new self();
        }
        return self::$instance;
    }

    public function add_place($id_org,$adresse){
        /*REMOVE ADRESSES*/
        $res = $this->dbh->prepare("DELETE FROM ost_organization_places WHERE id_org = :id_org");
        $res->execute(array(':id_org'=>$id_org));

        /*APPEND*/
        foreach($adresse as $adr){
            $res = $this->dbh->prepare("INSERT INTO ost_organization_places (id_org,Adresse) VALUES (:id_org,:adresse)");
            $res->execute(array(':id_org'=>$id_org,':adresse'=>$adr));
        }
        //return $res->fetchAll();
    }

    public function get_places($id_org){
        //echo $id_org;
        $res = $this->dbh->prepare("SELECT * FROM ost_organization_places WHERE id_org = :id_org");
        $res->execute(array(':id_org'=>$id_org));
        return $res->fetchAll();
    }
}

if(isset($_POST['add_place'])){
    Organisation::getInstance()->add_place($_POST['id_org'],$_POST['adresse']);
} else if(isset($_POST['get_places'])) {
    $places = Organisation::getInstance()->get_places($_POST['id_org']);
    print_r(json_encode($places));
}

?>
