<?php

class Contrat
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

    public function insertOrUpdateContrat($id,$id_org,$depart,$fin,$types,$commentaire){

        $depart = DateTime::createFromFormat('d/m/Y',$depart);
        $fin = DateTime::createFromFormat('d/m/Y',$fin);

        if($id === NULL){
            $res = $this->dbh->prepare("INSERT INTO ost_contrat (id_org,depart,fin,types,commentaire) VALUES (:id_org,:depart,:fin,:types,:commentaire)");
            $res->execute(array(':id_org'=>$id_org,
                                ':depart'=>$depart->format('Y-m-d'),
                                ':fin'=>$fin->format('Y-m-d'),
                                ':types'=>$types,
                                ':commentaire'=>$commentaire));
        } else {
            $res = $this->dbh->prepare("UPDATE ost_contrat SET depart = :depart, fin = :fin, types = :types, commentaire = :commentaire WHERE id=:id");
            $res->execute(array(':depart'=>$depart->format('Y-m-d'),
                                ':fin'=>$fin->format('Y-m-d'),
                                ':types'=>$types,
                                ':commentaire'=>$commentaire,
                                ':id'=>$id));
        }
        //return $res->fetchAll()[0];
    }

    public function getContrat($id){
        $res = $this->dbh->prepare("SELECT * FROM ost_contrat WHERE ost_contrat.id_org = :id");
        $res->execute(array(':id'=>$id));
        return $res->fetchAll()[0];
    }

    public function getCommentaire($id){
        $res = $this->dbh->prepare("SELECT commentaire FROM ost_contrat WHERE ost_contrat.id_org = :id");
        $res->execute(array(':id'=>$id));
        return $res->fetchAll()[0];
    }

    public function getTempsPasseContratType($org_id,$type){
        $res = $this->dbh->prepare("SELECT ost_rapport_horaires.arrive_inter,ost_rapport_horaires.depart_inter FROM ost_organization,ost_user,ost_ticket,ost_rapport,ost_rapport_horaires WHERE ost_organization.id = ost_user.org_id AND ost_user.id = ost_ticket.user_id AND ost_rapport.id_ticket = ost_ticket.ticket_id AND ost_rapport_horaires.id_rapport = ost_rapport.id AND ost_organization.id = :org_id AND ost_rapport.contrat LIKE :type");
        $res->execute(array(':org_id'=>$org_id,':type'=>'%'.$type.'%'));
        return $res->fetchAll();
    }

}

if(isset($_POST['insertOrUpdate'])){
    Contrat::getInstance()->insertOrUpdateContrat(isset($_POST['id']) ? $_POST['id'] : NULL,
            $_POST['id_org'],
            $_POST['depart'],
            $_POST['fin'],
            $_POST['types'],
            $_POST['commentaire']);
} else if (isset($_POST['getContrat'])){
     echo json_encode(Contrat::getInstance()->getCommentaire($_POST['id_org']));
}


?>
