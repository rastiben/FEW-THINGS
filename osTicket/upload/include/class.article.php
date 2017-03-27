<?php

class Article{

    private $data = null;
    public $reference = null;
    public $quantite = null;

    public function __construct($data){
        $this->data = array_values($data);
        $this->reference = $this->data[0];
        $this->quantite = $this->data[1];
    }

    public function getRef(){
        return $this->data[0];
    }

    public function getQuantite(){
        return $this->data[1];
    }

}

?>
