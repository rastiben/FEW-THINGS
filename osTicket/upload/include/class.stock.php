<?php

require_once(INCLUDE_DIR . 'bdd.stock.php');
require_once(INCLUDE_DIR . 'class.article.php');

class stock{

    public $articles = [];

    private $bdd_stock = null;

    public function __construct($agent){
        $this->bdd_stock = bdd_stock::getInstance();

        $stock = 'STOCK VOITURE ' . strtoupper($agent);
        $result = $this->bdd_stock->getStock($stock);

        while($myRow = odbc_fetch_array($result)){
            $this->addArticle($myRow);
        }
    }

    /*
    *Ajout d'un élément dans la collection
    */
    private function addArticle($data){
        array_push($this->articles,new Article($data));
    }


}

?>