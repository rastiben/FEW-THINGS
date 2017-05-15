<?php

require_once(INCLUDE_DIR.'class.contrats.php');

class ContratsAjaxAPI extends AjaxController {

  function addContrat(){

    include(STAFFINC_DIR . 'templates/contrat.add.php');

  }

  function createContrat(){
    if (($contrat = Contrat::fromVars($_POST)))
        Http::response(201, json_encode($contrat));
  }

}
