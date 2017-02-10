<?php

require_once(SCP_DIR . 'Request/GetInfos.php');
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

?>


<div class="">
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

