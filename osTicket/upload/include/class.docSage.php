<?php

require_once(INCLUDE_DIR.'bdd.docSage.php');

class docSage{

    public static function getBDD(){
        $BDD = bdd_docSage::getInstance();
        return $BDD;
    }

    public static function createDocument($org,$stock,$lines){
        //Pour chaque ligne
        $BDD = self::getBDD();

        //Récupération du lieu de livraison
        $LI_NO = $BDD->getLi_NO($org);
        $LI_NO = odbc_fetch_array($LI_NO)['LI_NO'];

        //Création de l'entete
        do{
            $DO_PIECE = self::getDoPiece($BDD);
            $ENTETE = $BDD->createDocEntete($org,$stock,$LI_NO,$DO_PIECE);
        } while ($ENTETE === false);

        $lines = json_decode($lines);
        $ligne = 1000;

        //Ajout des lignes choisi
        foreach($lines as $key=>$line){
            if($line->suiviStock == 1){
                 //Pour chaque numéro de série
                 foreach($line->sn as $key=>$sn){
                     $BDD->createDocLineSN($org,$DO_PIECE,$ligne,$line->reference,$line->stock,$line->designation,$line->prix,$sn);
                     $ligne += 1000;
                 }
            } else {
                $BDD->createDocLineCMUP($org,$DO_PIECE,$ligne,$line->reference,$line->quantite,$line->stock,$line->designation,$line->prix);
                $ligne += 1000;
            }
        }
        //$BDD = self::getBDD();
        //$BDD->createDocLine();
    }

    private static function getDoPiece($BDD){
        //Récupération du nouveau BL
        $DO_PIECE = $BDD->getNewNumBL();
        $DO_PIECE = odbc_fetch_array($DO_PIECE)['DC_PIECE'];
        return $DO_PIECE;
    }

}

?>
