<?php

require('staff.inc.php');
require INCLUDE_DIR . 'class.org.php';

$org = OrganisationCollection::getInstance();

//METHOD
$method = $_SERVER['REQUEST_METHOD'];

//ROOT
$url = $_SERVER[REQUEST_URI];

//Récupération des paramétre
$url = substr($url,strpos($url,".php")+5);

//Récupération des paramétre
$routes = array
(
    // actual path => filter
    'org'   => array('org', ':id'),
    'orgV'   => array('org', ':id', ':variable'),
    'orgA'   => array('org', ':action', ':id'),
    'orgs'   => array('orgs', ':name'),
    'userA' => array('user', ':action', ':id')
);

function dispatcher($url, $routes)
{
    $final_path         = FALSE;

    $url_path           = explode('/', $url);
    $url_path_length    = count($url_path);

    foreach($routes as $original_path => $filter)
    {
        // reset the parameters every time in case there is partial match
        $parameters     = array();

        // this filter is irrelevent
        if($url_path_length <> count($filter))
        {
            continue;
        }

        foreach($filter as $i => $key)
        {
            if(strpos($key, ':') === 0)
            {
                $parameters[substr($key, 1)]    = $url_path[$i];
            }
            // this filter is irrelevent
            else if($key != $url_path[$i])
            {
                continue 2;
            }
        }

        $final_path = $original_path;

        break;
    }

    return $final_path ? array('path' => $final_path, 'parameters' => $parameters) : FALSE;
}

$url = dispatcher($url, $routes);

if(strstr($url['path'],"org")){
    if($url['path'] == "orgs"){
        switch($method){
            case "GET":
                echo json_encode($org->searchByName($url['parameters']['name']));
                break;
        }
    } else {
        if($action = isset($url['parameters']['action'])){

        } else if($variable = isset($url['parameters']['variable'])) {
            $org = $org->lookUpById($url['parameters']['id'])[0];
            if($variable == "name"){
                echo $org->getName();
            }
        } else {
            switch($method){
                case "GET":
                    echo json_encode($org->lookUpById($url['parameters']['id']));
                    break;
            }
        }
    }

}  else if(strstr($url['path'],"userA")){
    if($action = isset($url['parameters']['action'])){
        if($action == "setOrg"){

        }
    }
}

?>
