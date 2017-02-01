<?php

class Agent
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

    public function staff_avatar($staff){
        $staff = strtr(utf8_decode($staff),
        utf8_decode(
        'ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ'),
        'SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy');

        $res = $this->dbh->prepare("SELECT avatar FROM ost_staff_avatar,ost_staff WHERE ost_staff.staff_id = ost_staff_avatar.id_staff AND CONCAT(ost_staff.firstname, ' ',ost_staff.lastname) = :staff");
        $res->execute(array(':staff'=>$staff));
        return $res->fetchAll();
    }
}

?>
