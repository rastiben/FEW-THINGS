<?php
    require_once('./Request/GetInfos.php');
    require_once('./Request/Atelier.php');

    $queue_columns = array(
        'number' => array(
            'width' => '7.4%',
            'heading' => __('Number'),
            ),
        'date' => array(
            'width' => '14.6%',
            'heading' => __('Date de création'),
            'sort_col' => 'created',
            ),
        'subject' => array(
            'width' => '26%',
            'heading' => __('Subject'),
            'sort_col' => 'cdata__subject',
            ),
        'name' => array(
            'width' => '14%',
            'heading' => __('From'),
            'sort_col' =>  'user__name',
            ),
        'org' => array(
            'width' => '14%',
            'heading' => __('Organisation'),
            'sort_col' =>  'user__name',
            ),
        'status' => array(
            'width' => '13%',
            'heading' => __('Status'),
            'sort_col' => 'status_id',
            ),
        'priority' => array(
            'width' => '8.4%',
            'heading' => __('Priority'),
            'sort_col' => 'cdata__:priority__priority_urgency',
            ),
        'plusPrepa' => array(
            'width' => '8.4%',
            'heading' => __('Preparation')
            ),
        'plusRepa' => array(
            'width' => '8.4%',
            'heading' => __('Reparation')
            )
        );

?>

    <div class="plan col-md-12">
        <h1 class="col-md-12">Plan de l'atelier : </h1>
        <div class="atelier col-md-9">
            <div class="bureau" id="un" data_planche="b1"></div>
            <div class="bureau" id="deux" data_planche="b2"></div>
            <div class="bureau" id="trois" data_planche="b3"></div>
            <div class="portable" id="un" data_planche="p1"></div>
            <div class="portable" id="deux" data_planche="p2"></div>
            <div class="portable" id="trois" data_planche="p3"></div>
            <div class="mur" id="un" data_planche="m1"></div>
            <div class="mur" id="deux" data_planche="m2"></div>
            <div class="mur" id="trois" data_planche="m3"></div>
            <div class="mur" id="quatre" data_planche="m4"></div>
            <div class="serveur" id="un" data_planche="s1"></div>
            <div class="serveur" id="deux" data_planche="s2"></div>
            <div class="serveur" id="trois" data_planche="s3"></div>
            <div class="serveur" id="quatre" data_planche="s4"></div>
            <div class="serveur" id="cinq" data_planche="s5"></div>
            <div class="serveur" id="six" data_planche="s6"></div>
        </div>
        <div class="enCours col-md-3">
            <h2>En cours</h2>
            <?php

            $orgPlanche = Atelier::getInstance()->get_org_planches();
            $orgList = [];
            foreach($orgPlanche as $obj){
                $orgList[$obj['planche_id']]=$obj['name'];
            }
            //print_r($orgList);
            //print_r($orgPlanche);

            ?>

            <div class="bureau1"><div class="color"></div><h4>B1 : <?php echo $orgList['1'] ?></h4></div>
            <div class="bureau2"><div class="color"></div><h4>B2 : <?php echo $orgList['2'] ?></h4></div>
            <div class="bureau3"><div class="color"></div><h4>B3 : <?php echo $orgList['3'] ?></h4></div>
            <div class="portable1"><div class="color"></div><h4>P1 : <?php echo $orgList['4'] ?></h4></div>
            <div class="portable2"><div class="color"></div><h4>P2 : <?php echo $orgList['5'] ?></h4></div>
            <div class="portable3"><div class="color"></div><h4>P3 : <?php echo $orgList['6'] ?></h4></div>
            <div class="mur1"><div class="color"></div><h4>M1 : <?php echo $orgList['7'] ?></h4></div>
            <div class="mur2"><div class="color"></div><h4>M2 : <?php echo $orgList['8'] ?></h4></div>
            <div class="mur3"><div class="color"></div><h4>M3 : <?php echo $orgList['9'] ?></h4></div>
            <div class="mur4"><div class="color"></div><h4>M4 : <?php echo $orgList['10'] ?></h4></div>
            <div class="serveur1"><div class="color"></div><h4>S1 : <?php echo $orgList['11'] ?></h4></div>
            <div class="serveur2"><div class="color"></div><h4>S2 : <?php echo $orgList['12'] ?></h4></div>
            <div class="serveur3"><div class="color"></div><h4>S3 : <?php echo $orgList['13'] ?></h4></div>
            <div class="serveur4"><div class="color"></div><h4>S4 : <?php echo $orgList['14'] ?></h4></div>
            <div class="serveur5"><div class="color"></div><h4>S5 : <?php echo $orgList['15'] ?></h4></div>
            <div class="serveur6"><div class="color"></div><h4>S6 : <?php echo $orgList['16'] ?></h4></div>
        </div>

            <div class="modal fade" id="fichesModal" data_planche="">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>-->
                  </div>
                  <div class="modal-body">
                    <div class="container home">
                       <div class="col-md-12">
                        <?php
                            //Recuperer les tickets de type atelier. Afficher numero affaire + nom client + num ticket
                            $atelier = TicketsInfos::getInstance()->atelier_tickets();
                        ?>
                        <table class="list atelierT" border="0" cellspacing="1" cellpadding="2" width="100%">
                        <thead>
                            <tr>
                                <?php
                                if ($thisstaff->canManageTickets()) { ?>
                                <th width="2%">&nbsp;</th>
                                <?php } ?>

                                <?php
                                // Swap some columns based on the queue.

                                unset($queue_columns['dept']);
                                unset($queue_columns['assignee']);

                                /*if ($search && !$status)
                                    unset($queue_columns['priority']);
                                else
                                    unset($queue_columns['status']);*/

                                // Query string
                                unset($args['sort'], $args['dir'], $args['_pjax']);
                                $qstr = Http::build_query($args);
                                // Show headers
                                foreach ($queue_columns as $k => $column) {
                                    echo sprintf( '<th width="%s"><a href="?sort=%s&dir=%s&%s"
                                            class="%s">%s</a></th>',
                                            $column['width'],
                                            $column['sort'] ?: $k,
                                            $column['sort_dir'] ? 0 : 1,
                                            $qstr,
                                            isset($column['sort_dir'])
                                            ? ($column['sort_dir'] ? 'asc': 'desc') : '',
                                            $column['heading']);
                                }
                                ?>
                            </tr>
                         </thead>
                         <tbody>
                            <?php
                                foreach ($atelier as $T) {
                                    $total += 1;
                                    $tid=$T['number'];
                                    ?>
                                <tr id="<?php echo $T['ticket_id']; ?>">
                                    <?php if($thisstaff->canManageTickets()) {

                                        $sel=false;
                                        if($ids && in_array($T['ticket_id'], $ids))
                                            $sel=true;
                                        ?>
                                    <td align="center" class="nohover">
                                        <input class="ckb" type="checkbox" name="tids[]"
                                            value="<?php echo $T['ticket_id']; ?>" <?php echo $sel?'checked="checked"':''; ?>>
                                    </td>
                                    <?php } ?>
                                    <td title="<?php echo $T['user__default_email__address']; ?>" nowrap>
                                      <a class="Icon <?php echo strtolower($T['source']); ?>Ticket preview"
                                        title="Preview Ticket"
                                        href="tickets.php?id=<?php echo $T['ticket_id']; ?>"
                                        data-preview="#tickets/<?php echo $T['ticket_id']; ?>/preview"
                                        ><?php echo $tid; ?></a></td>
                                    <td align="center" nowrap><?php echo Format::datetime($T[$date_col ?: 'lastupdate']) ?: $date_fallback; ?></td>
                                    <td>
                                        <span><?php echo $T['subject']; ?></span>
                    <?php               if ($T['attachment_count'])
                                            echo '<i class="small icon-paperclip icon-flip-horizontal" data-toggle="tooltip" title="'
                                                .$T['attachment_count'].'"></i>';
                                        if ($threadcount > 1) { ?>
                                            <span class="pull-right faded-more"><i class="icon-comments-alt"></i>
                                                <small><?php echo $threadcount; ?></small>
                                            </span>
                                        <?php } ?>
                                    </td>
                                    <td nowrap><div><?php
                                        if ($T['collab_count'])
                                            echo '<span class="pull-right faded-more" data-toggle="tooltip" title="'
                                                .$T['collab_count'].'"><i class="icon-group"></i></span>';
                                        ?><span class="truncate" style="max-width:<?php
                                            echo $T['collab_count'] ? '150px' : '170px'; ?>"><?php
                                        /*TO CHANGE*/
                                        $un = new UsersName($T['user__name']);
                                            echo '<a href="./users.php?id='. TicketsInfos::getInstance()->ticket_user_id($T['ticket_id']) .'#tickets">' . ucwords($T['firsname'] . ' ' . $T['name']) . '</a>';
                                        ?></span></div></td>
                                    <td nowrap><div><?php
                                        if ($T['collab_count'])
                                            echo '<span class="pull-right faded-more" data-toggle="tooltip" title="'
                                                .$T['collab_count'].'"><i class="icon-group"></i></span>';
                                        ?><span class="truncate" style="max-width:<?php
                                            echo $T['collab_count'] ? '150px' : '170px'; ?>"><?php
                                            echo '<a href="./orgs.php?id='. TicketsInfos::getInstance()->ticket_org_id($T['ticket_id']) .'#users">'. TicketsInfos::getInstance()->ticket_org_name($T['ticket_id']) .'</a>';
                                        ?></span></div></td>
                                    <?php
                                    if($search && !$status){
                                        $displaystatus=TicketStatus::getLocalById($T['status_id'], 'value', $T['status__name']);
                                        if(!strcasecmp($T['status__state'],'open'))
                                            $displaystatus="<b>$displaystatus</b>";
                                        echo "<td>$displaystatus</td>";
                                    } else { ?>
                                    <td class="nohover" align="center">
                                        <?php echo $T['status_name']; ?></td>
                                    <td class="nohover" align="center"
                                        style="background-color:<?php echo $T['cdata__:priority__priority_color']; ?>;">
                                        <?php echo $T['priority_desc']; ?></td>
                                    <td style="text-align:center"><a href="#" class="addContenu preparation btn btn-success">+</a></td>
                                    <td style="text-align:center"><a href="#" class="addContenu reparation btn btn-success">+</a></td>
                                    <?php
                                    }
                                    ?>
                                </tr>
                                <?php
                                } //end of foreach
                            if (!$total)
                                $ferror=__('There are no tickets matching your criteria.');
                            ?>
                        </tbody>
                        <tfoot>
                         <tr>
                            <td colspan="10">
                                <?php if($total && $thisstaff->canManageTickets()){ ?>
                                <?php echo __('Select');?>:&nbsp;
                                <a id="selectAll" href="#ckb"><?php echo __('All');?></a>&nbsp;&nbsp;
                                <a id="selectNone" href="#ckb"><?php echo __('None');?></a>&nbsp;&nbsp;
                                <a id="selectToggle" href="#ckb"><?php echo __('Toggle');?></a>&nbsp;&nbsp;
                                <?php }else{
                                    echo '<i>';
                                    echo $ferror?Format::htmlchars($ferror):__('Query returned 0 results.');
                                    echo '</i>';
                                } ?>
                            </td>
                         </tr>
                        </tfoot>
                        </table>
                      </div>
                    </div>
                    <div class="container fiche">
                        <div class="retour title">
                            <h4 style="float:left;width:80px">< planche</h4>
                            <h3 style="text-align:center;margin-right:80px"></h3>
                        </div>
                        <div class="repaTmpl" style="display:none">

                           <div class="col-md-12">
                                <div class="inputField col-md-6">
                                    <input id="typeAppareil" required>
                                    <label for="typeAppareil">Type d'appareil</label>
                                </div>

                                <div class="inputField col-md-6">
                                    <input id="mdp" required>
                                    <label for="mdp">MDP</label>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="inputField col-md-12">
                                    <textarea id="description" required></textarea>
                                    <label for="description">Description du problème</label>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="inputField col-md-12">
                                    <textarea id="comTech" required></textarea>
                                    <label for="comTech">Commentaire Technicien</label>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="inputField col-md-6">
                                    <input id="tempsInter" required>
                                    <label for="tempsInter">Temps d'intervention estimé</label>
                                </div>

                                <div class="inputField col-md-6">
                                    <input id="dateMiseADisposition" required>
                                    <label for="dateMiseADisposition">Date de mise à disposition souhaitée</label>
                                </div>
                            </div>

                            <div class="col-md-6 col-md-offset-6">
                                <div class="inputField col-md-12">
                                    <input id="visaClient" required>
                                    <label for="visaClient">Visa client</label>
                                </div>
                                <div class="inputField col-md-12">
                                    <input id="visaTech" required>
                                    <label for="visaTech">Visa tech</label>
                                </div>
                            </div>

                            <div class="col-md-12"><hr></div>

                            <div class="col-md-12">
                                <div class="inputField col-md-12">
                                    <textarea id="intervention" required></textarea>
                                    <label for="intervention">Intervention</label>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="inputField col-md-6">
                                    <input id="tempsPasse" required>
                                    <label for="tempsPasse">Temps passé</label>
                                </div>

                                <div class="inputField col-md-6">
                                    <input id="svisaTech" required>
                                    <label for="sVisaTech">Visa tech</label>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="inputField col-md-12">
                                    <textarea id="comIntervention" required></textarea>
                                    <label for="comIntervention">Commentaires</label>
                                </div>
                            </div>

                            <div class="col-md-12">
                               <div class="checkboxField col-md-12">
                                    <input type="checkbox" id="verifClient" required>
                                    <label for="verifClient">Vérification avec le client</label>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="inputField col-md-4">
                                    <input id="dateReprise" required>
                                    <label for="dateReprise">Date de reprise</label>
                                </div>
                            </div>

                        </div>

                   <div class="prepaTmpl" style="display:none">
                            <div class="col-md-12">
                                <div class="inputField col-md-6">
                                    <input id="nomDuPoste" required>
                                    <label for="nomDuPoste">Nom du poste</label>
                                </div>

                                <div class="inputField col-md-6">
                                    <textarea id="Model" required></textarea>
                                    <label for="Model">Modèle</label>
                                </div>
                            </div>

                            <div class="col-md-12">
                               <div class="checkboxField col-md-6">
                                    <input type="checkbox" id="etiquetage" required>
                                    <label for="etiquetage">Etiquetage du poste</label>
                                </div>
                                <div class="checkboxField col-md-6">
                                    <input type="checkbox" id="dossierSAV" required>
                                    <label for="dossierSAV">Création dossier savvdoc</label>
                                </div>
                            </div>



                            <div class="col-md-12">
                               <div class="checkboxField col-md-4">
                                    <input type="checkbox" id="septZip" required>
                                    <label for="septZip">7-zip</label>
                                </div>
                                <div class="checkboxField col-md-4">
                                    <input type="checkbox" id="acrobat" required>
                                    <label for="acrobat">Acrobat Reader</label>
                                </div>
                                <div class="checkboxField col-md-4">
                                    <input type="checkbox" id="flash" required>
                                    <label for="flash">Flash Player</label>
                                </div>
                            </div>

                            <div class="col-md-12">
                               <div class="checkboxField col-md-4">
                                    <input type="checkbox" id="java" required>
                                    <label for="java">Java</label>
                                </div>
                                <div class="checkboxField col-md-4">
                                    <input type="checkbox" id="pdf" required>
                                    <label for="pdf">PDF Creator</label>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="inputField col-md-12">
                                    <textarea id="autre" required></textarea>
                                    <label for="autre">Autre</label>
                                </div>
                            </div>




                            <div class="col-md-12">
                                <div class="inputField col-md-4">
                                    <input id="type" required>
                                    <label for="type">Type</label>
                                </div>
                                <div class="inputField col-md-4">
                                    <input id="userAccount" required>
                                    <label for="userAccount">Compte utilisateur créé</label>
                                </div>
                                <div class="inputField col-md-4">
                                    <input id="mdp" required>
                                    <label for="mdp">Mot de passe</label>
                                </div>
                                <div class="checkboxField col-md-12">
                                    <input type="checkbox" id="activation" required>
                                    <label for="activation">Activation</label>
                                </div>
                            </div>




                            <div class="col-md-12">
                                <div class="checkboxField col-md-4">
                                    <input type="checkbox" id="uninstall" required>
                                    <label for="uninstall">Désinstallation antivirus préinstallé</label>
                                </div>

                                <div class="checkboxField col-md-4">
                                    <input type="checkbox" id="maj" required>
                                    <label for="maj">M à J Windows et autres produits</label>
                                </div>
                                <div class="checkboxField col-md-4">
                                    <input type="checkbox" id="register" required>
                                    <label for="register">Enregistrement du produit</label>
                                </div>
                                <div class="checkboxField col-md-4">
                                    <input type="checkbox" id="verifActivation" required>
                                    <label for="verifActivation">Vérification activation windows</label>
                                </div>
                            </div>



                            <div class="col-md-12">
                                <div class="inputField col-md-12">
                                    <textarea id="divers" required></textarea>
                                    <label for="divers">Divers</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                  <div class="modal-footer">
                    <!--<button type="button" class="btn btn-primary"></button>-->
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Valider</button>
                  </div>
                </div>
              </div>
            </div>

        </div>





        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.1/angular.min.js"></script>
        <script src="../js/autosize.js"></script>

        <script type="text/javascript">

        $(function() {

            //Initiate
            $(document).off('click', '.atelier div');
            $(document).off('click', '.addContenu');
            $(document).off('click', '.contenu');
            $(document).off('click', '.retour h4');
            $(document).off('hidden.bs.modal', '.modal');

            autosize($('textarea'));

            //Gestion de l'atelier
            $(document).on('click', '.atelier div', function(e) {
                /*INIT*/
                $('.modal-body .container.home').show();
                $('.modal-body .container.fiche').hide();

                var planche = $(this);
                $('.modal-title').text((planche.attr('class') + ' ' + planche.attr('id')).replace(/\b[a-z]/g,function(f){return f.toUpperCase();}));
                $('#fichesModal').attr('data_planche',planche.attr('data_planche'));
                $.ajax({
                    url:'./Request/Atelier.php',
                    method:'POST',
                    data : {
                        request:'getContenuPlanche',
                        planche:planche.attr('data_planche')
                    }
                }).success(function(data){
                    var contenu = $.parseJSON(data);
                    $('.modal-body .contenu').remove();
                    $(contenu).each(function($number,$obj){
                        $('.modal-body div:first').prepend('<div class="col-md-3 contenu" id="">'+
                           '<div class="prepa">'+
                            '<img src="../assets/default/images/computer.png">'+
                            '<input value="'+ ($obj['type'] == "prepa"  ? "VD _ _ _ _" : "REPA") +'"/>'+
                            '</div>'+
                        '</div>');
                    });
                   $('#fichesModal').modal('toggle');
                });
            });

            //Ajouter une prepa/repa
            $(document).on('click','.addContenu',function(){
                var type = $(this).hasClass('preparation') ? "prepa" : "repa";
                var id = $(this).closest('tr').attr('id');
                var planche = $('#fichesModal').attr('data_planche');
                $.ajax({
                    url:'./Request/Atelier.php',
                    method:'POST',
                    data : {
                        request:'addContenu',
                        ticket_id:id,
                        planche:planche,
                        type:type
                    }
                }).success(function(data){
                    $('.modal-body div:first').prepend('<div class="col-md-3 contenu" id="">'+
                       '<div class="prepa">'+
                        '<img src="../assets/default/images/computer.png">'+
                        '<input value="'+ (type == "prepa"  ? "VD _ _ _ _" : "REPA") +'"/>'+
                        '</div>'+
                    '</div>');
                });
            });

            //Affichage de la fiche
            $(document).on('click','.contenu',function(){
                var type = $('input',this).val() == "REPA" ? "Fiche de réparation" : "Fiche de préparation";
                //CHANGEMENT DU TITRE
                $('.retour.title h3').html(type);

                /*GESTION DU CONTENU*/
                //$('.container.fiche .repaTmpl').remove();
                //$('.container.fiche .prepaTmpl').remove();
                $('.container.fiche .repaTmpl').css('display',type=="Fiche de réparation"?"block":"none");
                $('.container.fiche .prepaTmpl').css('display',type=="Fiche de préparation"?"block":"none");
                //$('.container.fiche').append(type == "Fiche de réparation" ? $('.repaTmpl').clone() : //$('.prepaTmpl').clone());

                //fade left out.
                $('.modal-body .container.home').hide("slide", { direction: "left" }, 600);
                //fade right in
                $('.modal-body .container.fiche').css('display','block');
                $('.modal-body .container.fiche').animate({
                   right : 0,
                    left : 0
                },{duration:600,queue:false},function(){
                    $(this).css('position','relative');
                    $(this).css('margin-top','0px');
                });


                $('.modal-body').animate({
                    height: $('.container.fiche').height()+30
                },{duration:600,queue:false});

            });

            //Retour sur la planche.
            $(document).on('click','.retour h4',function(){
                //fade right out
                $('.modal-body .container.fiche').css('position','absolute');
                $('.modal-body .container.fiche').css('margin-top','15px');
                $('.modal-body .container.fiche').animate({
                   right : '-100%',
                    left : '100%'
                },{duration:600,queue:false},function(){
                    $('.modal-body .container.fiche').css('display','none');
                });

                $('.modal-body').animate({
                    height: $('.container.home').height()+30
                },{duration:600,queue:false});

                //fade right in
                $('.modal-body .container.home').show("slide", { direction: "left" }, 600);
            });

            $(document).on('hidden.bs.modal','.modal', function () {
                //INIT
                $('.modal-body .container.fiche').css('right','-100%');
                $('.modal-body .container.fiche').css('left','100%');
                $('.modal-body .container.fiche').css('display','none');
                $('.modal-body').css('height', '100%');
            });


        });

        </script>
