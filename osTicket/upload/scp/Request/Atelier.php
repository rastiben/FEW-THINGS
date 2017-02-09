<?php

class Atelier
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

    public function add_contenu($ticket_id,$planche,$type){
        $res = $this->dbh->prepare("INSERT INTO ost_atelier_planche_contenu (ticket_id,planche_id,type_id)
        VALUES (:ticket_id,
        (SELECT id FROM ost_atelier_planche WHERE planche = :planche),
        (SELECT id FROM ost_atelier_contenu_type WHERE type = :type))");
        $res->execute(array(':ticket_id'=>$ticket_id,':planche'=>$planche,':type'=>$type));
        //return $res->fetchAll();
        return $this->dbh->lastInsertId();
    }

    public function get_org_planches(){
        $res = $this->dbh->prepare("SELECT ost_organization.name,ost_atelier_planche_contenu.planche_id
        FROM ost_user,ost_organization,ost_user__cdata,ost_ticket,ost_atelier_planche,ost_atelier_planche_contenu,ost_atelier_contenu_type
        WHERE ost_user.org_id = ost_organization.id
        AND ost_user.id = ost_user__cdata.user_id
        AND ost_ticket.user_id = ost_user.id
        AND ost_ticket.ticket_id = ost_atelier_planche_contenu.ticket_id
        AND ost_atelier_planche.id = ost_atelier_planche_contenu.planche_id
        AND ost_atelier_contenu_type.id = ost_atelier_planche_contenu.type_id");
        $res->execute();
        return $res->fetchAll();
    }

    public function get_contenu_planche($planche){
        $res = $this->dbh->prepare("SELECT *
        FROM ost_atelier_planche_contenu,ost_atelier_planche,ost_atelier_contenu_type
        WHERE ost_atelier_planche.id = ost_atelier_planche_contenu.planche_id
        AND ost_atelier_contenu_type.id = ost_atelier_planche_contenu.type_id
        AND ost_atelier_planche.planche = :planche");
        $res->execute(array(':planche'=>$planche));
        return $res->fetchAll();
    }

    public function get_prepa($ticket_id){
        $res = $this->dbh->prepare("SELECT ost_form_entry_values.value
        FROM ost_ticket,ost_form_entry,ost_form_entry_values
        WHERE ost_form_entry.object_id = ost_ticket.ticket_id
        AND ost_form_entry_values.entry_id = ost_form_entry.id
        AND ost_form_entry_values.field_id = '46'
        AND ost_ticket.ticket_id = :ticket_id");
        $res->execute(array(':ticket_id'=>$ticket_id));
        return $res->fetchAll();
    }
}

if(isset($_REQUEST['request'])){
    if($_REQUEST['request'] == 'getPrepa'){
        echo json_encode(Atelier::getInstance()->get_prepa($_REQUEST['ticket_id']));
    } else if($_REQUEST['request'] == 'addContenu'){
        return Atelier::getInstance()->add_contenu($_REQUEST['ticket_id'],$_REQUEST['planche'],$_REQUEST['type']);
    } else if($_REQUEST['request'] == 'getContenuPlanche'){
        echo json_encode(Atelier::getInstance()->get_contenu_planche($_REQUEST['planche']));
    }
}

?>
