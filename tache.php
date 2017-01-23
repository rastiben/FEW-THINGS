<?php

    include_once('./osTicket/upload/main.inc.php');
    include_once('./osTicket/upload/include/class.mailer.php');

    $hostname = 'localhost';
    $username = 'root';
    $password = '';
    $dbh;

    try{
        $dbh = new PDO('mysql:host=localhost;dbname=osticket', $username, $password);
    }catch(PDOException $e){
        die($e);
    }

    /*Récupération des contrats*/
    $res = $dbh->prepare("SELECT ost_contrat.depart,ost_contrat.fin,ost_organization.name FROM ost_contrat,ost_organization WHERE ost_organization.id = ost_contrat.id_org");
    $res->execute(array(':id'=>$id));
    $result = $res->fetchAll();

    /*Envoi des mails si besoin est*/
    $mailer = new Mailer($this);

    foreach($result as $contrat){

        $d1 = new DateTime('now');
        $d2 = new DateTime($contrat['fin']);

        $totalMonth = $d1->diff($d2)->m + ($d1->diff($d2)->y*12);

        if($totalMonth+1 <= 3){
            $subject = 'Le contrat de maintenance de ' . $contrat['name'] . ' arrive à échéance ';
            $message = '<b>Client : <b>' . $contrat['name'];
            $message = 'Le contrat de maintenance de ' . $contrat['name'] . ' arrive à échéance dans ' . ($totalMonth+1) . ' mois.';
            $mailer->send('brastier@viennedoc.com', $subject, $message, null);
        }
    }

?>
