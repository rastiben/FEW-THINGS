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


if(isset($_REQUEST['request']) && $_REQUEST['request'] == 'getPrepa'){
    echo json_encode(Atelier::getInstance()->get_prepa($_REQUEST['ticket_id']));
}

?>
