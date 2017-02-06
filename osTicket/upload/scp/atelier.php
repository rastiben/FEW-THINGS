<?php

require('staff.inc.php');
//require_once(INCLUDE_DIR.'class.ticket.php');
//require_once(INCLUDE_DIR.'class.dept.php');
//require_once(INCLUDE_DIR.'class.filter.php');
//require_once(INCLUDE_DIR.'class.canned.php');
//require_once(INCLUDE_DIR.'class.json.php');
//require_once(INCLUDE_DIR.'class.dynamic_forms.php');
//require_once(INCLUDE_DIR.'class.export.php');       // For paper sizes

$nav->setActiveTab('atelier');

$nav->addSubMenu(array('desc'=>'Tickets',
                            'title'=>'Tickets Atelier',
                            'href'=>'atelier.php?tabs=tickets',
                            'iconclass'=>'Ticket'));

$nav->addSubMenu(array('desc'=>'Plan',
                            'title'=>'Plan de l\'atelier',
                            'href'=>'atelier.php?tabs=plan',
                            'iconclass'=>'Plan'));

if(isset($_REQUEST['tabs'])){
     if($_REQUEST['tabs'] == 'tickets'){
         $nav->setActiveSubMenu(1);
         $inc = 'atelier_tickets.inc.php';
     } else {
         $nav->setActiveSubMenu(2);
         $inc = 'atelier_plan.inc.php';
     }
} else {
    $nav->setActiveSubMenu(1);
    $inc = 'atelier_tickets.inc.php';
}

require_once(STAFFINC_DIR.'header.inc.php');
require_once(STAFFINC_DIR.$inc);
require_once(STAFFINC_DIR.'footer.inc.php');
