<?php

//require_once('../../main.inc.php');
//require_once('../../include/class.staff.php');
//require_once('../include/class.csrf.php');

//$thisstaff = StaffAuthenticationBackend::getUser();

/*
*Classe Statistique.
*Une fonction pour chaque statistique.
*Initialisation à la connexion à la base de données dans le constructeur.
*/
class Rapport
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

    public function getRapportsHoraires($RapportID){
        $res = $this->dbh->prepare("SELECT id,arrive_inter,depart_inter,comment FROM ost_rapport_horaires WHERE id_rapport = :rapport_id");
        $res->execute(array(':rapport_id'=>$RapportID));

        return $res->fetchAll();
    }

    public function getRapports($ticketID){
        $res = $this->dbh->prepare("SELECT id,date_rapport,date_inter,firstname,lastname FROM ost_rapport,ost_staff WHERE ost_rapport.id_agent = ost_staff.staff_id AND id_ticket = :ticketID");
        $res->execute(array(':ticketID'=>$ticketID));

        return $res->fetchAll();
    }

    public function getRapport($id){
        $res = $this->dbh->prepare("SELECT date_rapport,date_inter,lastname FROM ost_rapport,ost_staff WHERE ost_rapport.id_agent = ost_staff.staff_id AND id = :ID");
        $res->execute(array(':ID'=>$id));

        return $res->fetchAll();
    }

    public function addRapport($ticketID,$agentID,$dateInter,$arriveInter,$departInter,$symptomesObservations)
    {
        $date_rapport = date('Y-m-d');
        $date = DateTime::createFromFormat('d/m/Y', $dateInter);

        $arrive = DateTime::createFromFormat('d/m/Y H:i', $date->format('d/m/Y') . ' ' . $arriveInter);
        $depart = DateTime::createFromFormat('d/m/Y H:i', $date->format('d/m/Y') . ' ' . $departInter);

        /*AJOUT DU RAPPORT*/
        $res = $this->dbh->prepare("INSERT INTO ost_rapport (id_ticket,id_agent,date_rapport,date_inter) VALUES (:ticket_id,:id_agent,:date_rapport,:date_inter)");
        $res->execute(array(':ticket_id'=>$ticketID,':id_agent'=>$agentID,':date_rapport'=>$date_rapport,':date_inter'=>$date->format('Y-m-d')));

        $rapport_id = $this->dbh->lastInsertId();

        /*AJOUT DU PREMIER HORAIRES*/
        $res = $this->dbh->prepare("INSERT INTO ost_rapport_horaires (id_rapport,arrive_inter,depart_inter,comment) VALUES (:rapport_id,:arrive_inter,:depart_inter,:comment)");
        $res->execute(array(':rapport_id'=>$rapport_id,':arrive_inter'=>$arrive->format('Y-m-d H:i:s'),':depart_inter'=>$depart->format('Y-m-d H:i:s'),':comment'=>$symptomesObservations));

        //echo $this->dbh->lastInsertId();
    }
    public function addHoraires($rapportID,$dateInter,$arriveInter,$departInter,$symptomesObservations)
    {
        $date = DateTime::createFromFormat('d/m/Y', $dateInter);

        $arrive = DateTime::createFromFormat('d/m/Y H:i', $date->format('d/m/Y') . ' ' . $arriveInter);
        $depart = DateTime::createFromFormat('d/m/Y H:i', $date->format('d/m/Y') . ' ' . $departInter);

        $res = $this->dbh->prepare("INSERT INTO ost_rapport_horaires (id_rapport,arrive_inter,depart_inter,comment) VALUES (:rapport_id,:arrive_inter,:depart_inter,:comment)");
        $res->execute(array(':rapport_id'=>$rapportID,':arrive_inter'=>$arrive->format('Y-m-d H:i:s'),':depart_inter'=>$depart->format('Y-m-d H:i:s'),':comment'=>$symptomesObservations));

    }

    public function updateHoraire($horaireID,$dateInter,$arriveInter,$departInter,$symptomesObservations)
    {
        $date = DateTime::createFromFormat('d/m/Y', $dateInter);

        $arrive = DateTime::createFromFormat('d/m/Y H:i', $date->format('d/m/Y') . ' ' . $arriveInter);
        $depart = DateTime::createFromFormat('d/m/Y H:i', $date->format('d/m/Y') . ' ' . $departInter);

        $res = $this->dbh->prepare("UPDATE ost_rapport_horaires SET arrive_inter = :arrive_inter, depart_inter = :depart_inter, comment = :comment WHERE id = :horaireID");
        $res->execute(array(':arrive_inter'=>$arrive->format('Y-m-d H:i:s'),':depart_inter'=>$depart->format('Y-m-d H:i:s'),':comment'=>$symptomesObservations,':horaireID'=>$horaireID));

    }

}

/*
*Si un membre du staff est connecté, une redirection est effectué vers la bonne fonction.
*/
/*if (!$thisstaff || !$thisstaff->getId() || !$thisstaff->isValid()) {
    return "error";
}
else{*/
if(isset($_POST['addRapport'])){
    Rapport::getInstance()->addRapport($_POST['ticket_id'],$_POST['agent_id'],$_POST['date_inter'],$_POST['arrive_inter'],$_POST['depart_inter'],$_POST['symptomesObservations']);
    header('Location: ../tickets.php?id=' . $_POST['ticket_id']);
}
else if(isset($_POST['addHoraires'])){
    Rapport::getInstance()->addHoraires($_POST['rapport_id'],$_POST['date_inter'],$_POST['arrive_inter'],$_POST['depart_inter'],$_POST['symptomesObservations']);
    //header('Location: ../tickets.php?id=' . $_POST['ticket_id']);
}
else if(isset($_POST['updateHoraire'])){
    Rapport::getInstance()->updateHoraire($_POST['horaire_id'],$_POST['date_inter'],$_POST['arrive_inter'],$_POST['depart_inter'],$_POST['symptomesObservations']);
    //header('Location: ../tickets.php?id=' . $_POST['ticket_id']);
}

//}

//GetTicketForAgent
//echo "toto";

?>
