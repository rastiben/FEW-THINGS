<?php

class ContratModel extends VerySimpleModel {
    static $meta = array(
        'table' => 'ost_contrat',
        'pk' => array('id')
    );
}

class Contrat extends ContratModel
implements TemplateVariable {

    var $_entries;
    var $_forms;

    static function getVarScope() {
        return '';
    }

    static function fromVars($vars, $create=true, $update=false) {
        //$vars = array_filter( $vars );
        //var_dump($vars);
        //die();

        if ($create) {
            $contrat = new Contrat(array(
                'code' => $vars['code']
            ));
            // Is there an organization registered for this domain
            /*list($mailbox, $domain) = explode('@', $vars['email'], 2);
            if (isset($vars['org_id']))
                $user->set('org_id', $vars['org_id']);
            elseif ($org = Organization::forDomain($domain))
                $user->setOrganization($org, false);*/

            try {
                $contrat->save(true);
                /*$user->emails->add($user->default_email);
                // Attach initial custom fields
                $user->addDynamicData($vars);*/
            }
            catch (OrmException $e) {
                return null;
            }
            //Signal::send('user.created', $user);
        }
        /*elseif ($update) {
            $errors = array();
            $user->updateInfo($vars, $errors, true);
        }*/

        return $contrat;
    }
  }
