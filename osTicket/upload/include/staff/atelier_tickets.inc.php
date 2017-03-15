<?php

require_once(SCP_DIR . 'Request/Tickets.php');
$atelier = TicketsInfos::getInstance()->atelier_tickets();

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
        );

$orgsC = OrganisationCollection::getInstance();
//$org = $orgsC->lookUpById($user->getOrgId())[0];

?>


<div class="">

  <!--http://localhost:8080/osTicket/upload/scp/tickets.php?a=open-->
   <div class="newContent col-md-12">
        <div class="content col-md-12">
            <a href="http://localhost:8080/osTicket/upload/scp/tickets.php?a=open" class="no-pjax"><img src="../assets/atelier/newTicket.png"></a>
        </div>
   </div>

   <!--MODAL-->
<div class="modal fade" id="fichesModal" data_planche="" data_id_contenu="" data_staff="<?php echo $thisstaff->getId() ?>">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success sendContenu">Ajouter</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler</button>
            </div>
        </div>
    </div>
</div>

   <script>
       $(function(){

           $(document).off('click','.content img');
           $(document).off('click','.sendContenu');

           var planche = new Planche();
           var id = "";
           var type = "";

           /*$(document).on('click','.content img',function(e){

               if(!$(this).parent().is('a')){
                   if($(this).attr('id') == "prepair"){
                        $('.modal-title').text('Ajout d\'une préparation');
                        type = "préparation";
                   } else {
                        $('.modal-title').text('Ajout d\'une réparation');
                       type = "réparation";
                   }
                   //Récupération de l'id du ticket
                   id = $('input[type="radio"]:checked').closest('tr').attr('id');

                   //Affichage de la question
                   $('.modal-body').html('<p>Voulez vous vraiment ajouter cette nouvelle '+ type +' sur le ticket : '+ id +' ?</p> \
                                        <label>Affectation à une planche : (Optionnel)</label> \
                                        <select class="custom-select"> \
                                        <option id="null" selected>Open this select menu</option> \
                                        <option id="b1" value="1">Bureau 1</option> \
                                        <option id="b2" value="1">Bureau 2</option> \
                                        <option id="b3" value="1">Bureau 3</option> \
                                        <option id="p1" value="1">Portable 1</option> \
                                        <option id="p2" value="1">Portable 2</option> \
                                        <option id="p3" value="1">Portable 3</option> \
                                        <option id="m1" value="1">Mur 1</option> \
                                        <option id="m2" value="1">Mur 2</option> \
                                        <option id="m3" value="1">Mur 3</option> \
                                        <option id="m4" value="1">Mur 4</option> \
                                        <option id="s1" value="1">Serveur 1</option> \
                                        <option id="s2" value="1">Serveur 2</option> \
                                        <option id="s3" value="1">Serveur 3</option> \
                                        <option id="s4" value="1">Serveur 4</option> \
                                        <option id="s5" value="1">Serveur 5</option> \
                                        <option id="s6" value="1">Serveur 6</option> \
                                        </select>');

                   if(id != undefined)
                        $('.modal').modal('toggle');
               }
           });*/

           $(document).on('click','.sendContenu',function(){
               $('.modal').modal('toggle');
               var pl = $('.custom-select option:selected').attr('id');
               planche.addContenu(id,type == "préparation" ? "prepa" : "repa",pl);
           });


       });
   </script>

   <div class="filter">
       <ul class="col-md-12">
            <li class="col-md-5"><p>Liste completes</p></li>
            <li class="col-md-5"><p>Entrées</p></li>
            <li class="col-md-5"><p>Planche</p></li>
            <li class="col-md-5"><p>Sorties</p></li>
            <li class="col-md-5"><p>RMA</p></li>
       </ul>
   </div>
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
                <?php

                $ticket = TicketsInfos::getInstance();
                $org = $orgsC->lookUpById(TicketsInfos::getInstance()->ticket_org_id($T['ticket_id']))[0];
                if(!empty($org)){
                    $id = $org->getId();
                    $orgName = $org->getName();
                }

                if($thisstaff->canManageTickets()) {

                    $sel=false;
                    if($ids && in_array($T['ticket_id'], $ids))
                        $sel=true;
                    ?>
                <td align="center" class="nohover">
                    <input class="ckb" type="radio" name="selection">
                </td>
                <?php } ?>
                <td title="<?php echo $T['user__default_email__address']; ?>" nowrap>
                  <a class="Icon <?php echo strtolower($T['source']); ?>Ticket preview no-pjax"
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
                        echo '<a href="./users.php?id='. $ticket->ticket_user_id($T['ticket_id']) .'#tickets">' . ucwords($T['firsname'] . ' ' . $T['name']) . '</a>';
                    ?></span></div></td>
                <td nowrap><div><?php
                    if ($T['collab_count'])
                        echo '<span class="pull-right faded-more" data-toggle="tooltip" title="'
                            .$T['collab_count'].'"><i class="icon-group"></i></span>';
                    ?><span class="truncate" style="max-width:<?php
                        echo $T['collab_count'] ? '150px' : '170px'; ?>"><?php
                        echo '<a href="./orgs.php?id='. $id .'#users">'. $orgName .'</a>';
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
        <td colspan="8">
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

