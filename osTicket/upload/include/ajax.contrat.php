<?php

require_once(INCLUDE_DIR.'class.contrats.php');

class ContratsAjaxAPI extends AjaxController {

  function addContrat(){

    include(STAFFINC_DIR . 'templates/contrat.add.php');

  }

  function createContrat(){
    $vars = (array)json_decode(file_get_contents("php://input"));

    if (($contrat = Contrat::fromVars($vars)))
        Http::response(201, json_encode($contrat));
  }

  function index(){
    $contrats = ContratModel::objects();
    $temp = [];

    foreach ($contrats as $key => $value) {
      //echo json_encode($val)
      array_push($temp,$value->ht);
    }

    Http::response(200, json_encode($temp));
  }

}
