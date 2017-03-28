<?php

class Article{

    private $data = null;
    public $reference = null;
    public $quantite = null;
    public $suiviStock = null;
    public $stock = null;
    public $sn = [];

    public function __construct($data){
        $this->data = array_values($data);
        $this->reference = $this->data[0];
        $this->quantite = $this->data[1];
        $this->suiviStock = $this->data[2];
        $this->stock = $this->data[3];
    }

}

?>
