<?php



require_once('staff.inc.php');
require_once(INCLUDE_DIR . 'class.org.php');
require_once(INCLUDE_DIR . 'class.users.php');
require_once(INCLUDE_DIR . 'class.stats.php');
require_once(INCLUDE_DIR . 'class.contrat.php');
require_once(INCLUDE_DIR . 'class.stock.php');
require_once(INCLUDE_DIR . 'class.docSage.php');
//METHOD
$method = $_SERVER['REQUEST_METHOD'];

//ROOT
$url = $_SERVER[REQUEST_URI];

//Récupération des paramétre
$url = substr($url,strpos($url,".php")+5);

//Récupération des paramétre
//A = Action sur un élément
//V = variable
//C = Action sur un ensemble d'élément

$routes = array
(
    // actual path => filter
    'org' => array('org', ':id'),
    'orgV' => array('org', ':id', ':variable'),
    'orgA' => array('org', ':action', ':id'),
    'orgs' => array('orgs', ':name'),
    'userC' => array('user',':action'),
    'userA' => array('user', ':action', ':id'),
    'stats' => array('stats',':objet',':id'),
    'contrat' => array('contrat',':orgId'),
    'stock' => array('stock',':agent'),
    'stockSN' => array('stock','sn',':reference',':agent'),
    'docSageE' => array('docSage',':toCreate',':tiers',':stock'),
    'docSageL' => array('docSage',':toCreate')
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
/*if(strstr($url['path'],"toto")){
    $org = OrganisationCollection::getInstance();
    $org->toto();
}*/
/*REQUETES ORGANISATION*/
if(strstr($url['path'],"org")){
    $org = OrganisationCollection::getInstance();
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
/*REQUETES USERS*/
}  else if(strstr($url['path'],"user")){
    $users = userCollection::getInstance();
    if($action = isset($url['parameters']['action'])){
        if($id = isset($url['parameters']['id'])){

        } else {
            if($action == "maj"){
                $users->majBaseUser();
            }
            if($action == "setOrg"){

            }
        }
    }
/*REQUETES STATISTIQUE*/
} else if(strstr($url['path'],"stats")){
    $stats = new stats();
    if(isset($url['parameters']['objet'])){
        $objet = $url['parameters']['objet'];
        if($objet == "org"){
            $id = $url['parameters']['id'];
            $date1 = $_POST['sDate'];
            $date2 = $_POST['eDate'];
            echo $stats->statsTicketFromOrg($id,$date1,$date2);
        } else if($objet == "agent"){
            $id = $url['parameters']['id'];
            $date1 = $_POST['sDate'];
            $date2 = $_POST['eDate'];
            echo $stats->statsTicketForAgent($id,$date1,$date2);
        }
    }
/*REQUETES CONTRAT*/
} else if(strstr($url['path'],"contrat")){
    $contrat = contratCollection::getInstance();
    if($action = isset($url['parameters']['action'])){

    } else if($id = isset($url['parameters']['orgId'])){
        echo json_encode($contrat->lookUpById($id));
    }
/*REQUETES STOCKS*/
} else if(strstr($url['path'],"stock")){
    if($url['path'] == 'stockSN'){
        $reference = $url['parameters']['reference'];
        $agent = $url['parameters']['agent'];
        $stock = stock::getSN($reference,$agent);
        echo json_encode($stock);
    }else if(isset($url['parameters']['agent'])){
        $agent = $url['parameters']['agent'];
        $stock = new stock($agent);
        echo json_encode($stock->articles);
    }
    //orgid
} else if(strstr($url['path'],"docSage")){
    if(isset($url['parameters']['toCreate'])){
        if($url['parameters']['toCreate'] == 'entete'){
            $tiers = $url['parameters']['tiers'];
            $stock = $url['parameters']['stock'];
            docSage::createDocEntete($tiers,$stock);
        } else if($url['parameters']['toCreate'] == 'lines') {
            $angular_http_params = (array)json_decode(trim(file_get_contents('php://input')));
            $org = $angular_http_params['org'];
            $stock = $angular_http_params['stock'];
            $lines = $angular_http_params['lines'];
            docSage::createDocLines($org,$stock,$lines);
        }
    }
}
?>
