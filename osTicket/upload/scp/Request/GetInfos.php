<?php

/*class TicketsInfos{

    public static $hostname;
    public static $username;
    public static $password;
    public static $dbh;

    function __construct(){
        $this->hostname = 'localhost';
        $this->username = 'root';
        $this->password = '';
        try{
            $this->dbh = new PDO('mysql:host=localhost;dbname=osticket', $this->username, $this->password);
        }catch(PDOException $e){
            die($e);
        }
    }

    static function ticket_org_name(){
        $res = $this->dbh->prepare("SELECT user_id FROM ost_ticket");
        $res->execute();
        return $res->fetchAll()[0];
    }

}*/

class TicketsInfos
{
    private static $instance;
    private static $hostname;
    private static $username;
    private static $password;
    private static $dbh;

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

    /*public function ticket_user_phone($ticketID)
    {
        $res = $this->dbh->prepare("SELECT ost_user__cdata.phone FROM ost_ticket,ost_user,ost_user__cdata WHERE ost_ticket.user_id = ost_user.id AND ost_user.id = ost_user__cdata.user_id AND ost_ticket.ticket_id = :ticketID");
        $res->execute(array(':ticketID'=>$ticketID));
        return $res->fetchAll()[0]['phone'];
    }*/
    public function numberOfOpenTickets(){
        $res = $this->dbh->prepare("SELECT COUNT(*) FROM ost_ticket WHERE ost_ticket.status_id = '1'");
        $res->execute();
        return $res->fetchAll()[0]['COUNT(*)'];
    }

    public function ticket_user_id($ticketID)
    {
        $res = $this->dbh->prepare("SELECT ost_ticket.user_id FROM ost_ticket WHERE ost_ticket.ticket_id = :ticketID");
        $res->execute(array(':ticketID'=>$ticketID));
        return $res->fetchAll()[0]['user_id'];
    }

    public function ticket_org_id($ticketID)
    {
        $res = $this->dbh->prepare("SELECT ost_organization.id FROM ost_ticket,ost_user,ost_organization WHERE ost_ticket.user_id = ost_user.id AND ost_user.org_id = ost_organization.id AND ost_ticket.ticket_id = :ticketID");
        $res->execute(array(':ticketID'=>$ticketID));
        return $res->fetchAll()[0]['id'];
    }

    public function ticket_org_name($ticketID)
    {
        $res = $this->dbh->prepare("SELECT ost_organization.name FROM ost_ticket,ost_user,ost_organization WHERE ost_ticket.user_id = ost_user.id AND ost_user.org_id = ost_organization.id AND ost_ticket.ticket_id = :ticketID");
        $res->execute(array(':ticketID'=>$ticketID));
        return $res->fetchAll()[0]['name'];
    }
}


?>
