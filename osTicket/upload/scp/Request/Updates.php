<?php

/*require_once('../../main.inc.php');
require_once('../../include/class.staff.php');
//require_once('../include/class.csrf.php');

$thisstaff = StaffAuthenticationBackend::getUser();

class Updates
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

    public function update_ticket_status($ticketID)
    {
        $res = $this->dbh->prepare("SELECT ost_organization.name FROM ost_ticket,ost_user,ost_organization WHERE ost_ticket.user_id = ost_user.id AND ost_user.org_id = ost_organization.id AND ost_ticket.ticket_id = :ticketID");
        $res->execute(array(':ticketID'=>$ticketID));
        return $res->fetchAll()[0]['name'];
    }
}


if (!$thisstaff || !$thisstaff->getId() || !$thisstaff->isValid()) {
    return "error";
}
else{
    if(isset($_POST['updateTicketStatus'])){
        $result = Updates::getInstance()->getTicketFromOrg($_POST['org'],$_POST['sDate'],$_POST['eDate']);
        echo $result;
    }
}*/

?>
