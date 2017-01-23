<?php
require_once(SCP_DIR.'Request/Contrat.php');

if(!defined('OSTSCPINC') || !$thisstaff || !is_object($org)) die('Invalid path');

?>
<table width="100%" cellpadding="2" cellspacing="0" border="0">
    <tr>
        <td width="50%" class="has_bottom_border">
             <h2><a href="orgs.php?id=<?php echo $org->getId(); ?>"
             title="Reload"><i class="icon-refresh"></i> <?php echo $org->getName(); ?></a></h2>
        </td>
        <td width="50%" class="right_align has_bottom_border">
<?php if ($thisstaff->hasPerm(Organization::PERM_DELETE)) { ?>
            <a id="org-delete" class="red button action-button org-action"
            href="#orgs/<?php echo $org->getId(); ?>/delete"><i class="icon-trash"></i>
            <?php echo __('Delete Organization'); ?></a>
<?php } ?>
<?php if ($thisstaff->hasPerm(Organization::PERM_EDIT)) { ?>
            <span class="action-button" data-dropdown="#action-dropdown-more">
                <i class="icon-caret-down pull-right"></i>
                <span ><i class="icon-cog"></i> <?php echo __('More'); ?></span>
            </span>
<?php } ?>
            <div id="action-dropdown-more" class="action-dropdown anchor-right">
              <ul>
<?php if ($thisstaff->hasPerm(Organization::PERM_EDIT)) { ?>
                <li><a href="#ajax.php/orgs/<?php echo $org->getId();
                    ?>/forms/manage" onclick="javascript:
                    $.dialog($(this).attr('href').substr(1), 201);
                    return false"
                    ><i class="icon-paste"></i>
                    <?php echo __('Manage Forms'); ?></a></li>
<?php } ?>
              </ul>
            </div>
        </td>
    </tr>
</table>
<table class="ticket_info" cellspacing="0" cellpadding="0" width="100%" border="0">
    <tr>
        <td width="50%">
            <table border="0" cellspacing="" cellpadding="4" width="100%">
                <tr>
                    <th width="150"><?php echo __('Name'); ?>:</th>
                    <td>
<?php if ($thisstaff->hasPerm(Organization::PERM_EDIT)) { ?>
                    <b><a href="#orgs/<?php echo $org->getId();
                    ?>/edit" class="org-action"><i
                        class="icon-edit"></i>
<?php }
                    echo $org->getName();
    if ($thisstaff->hasPerm(Organization::PERM_EDIT)) { ?>
                    </a></b>
<?php } ?>
                    </td>
                </tr>
                <tr>
                    <th><?php echo __('Account Manager'); ?>:</th>
                    <td><?php echo $org->getAccountManager(); ?>&nbsp;</td>
                </tr>
            </table>
        </td>
        <td width="50%" style="vertical-align:top">
            <table border="0" cellspacing="" cellpadding="4" width="100%">
                <tr>
                    <th width="150"><?php echo __('Created'); ?>:</th>
                    <td><?php echo Format::datetime($org->getCreateDate()); ?></td>
                </tr>
                <tr>
                    <th><?php echo __('Last Updated'); ?>:</th>
                    <td><?php echo Format::datetime($org->getUpdateDate()); ?></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<br>
<div class="clear"></div>

<ul class="clean tabs" id="orgtabs">
    <li class="active"><a href="#users"><i
    class="icon-user"></i>&nbsp;<?php echo __('Users'); ?></a></li>
    <li><a href="#tickets"><i
    class="icon-list-alt"></i>&nbsp;<?php echo __('Tickets'); ?></a></li>
    <li><a href="#notes"><i
    class="icon-pushpin"></i>&nbsp;<?php echo __('Notes'); ?></a></li>
    <li><a href="#contrat">&nbsp;<?php echo __('Contrat'); ?></a></li>
</ul>
<div id="orgtabs_container">
<div class="tab_content" id="contrat" style="display:none">

<!--GET CONTRAT INFO-->
<?php
    $contrat = Contrat::getInstance()->getContrat($org->getId());

    if(empty($contrat) === FALSE){



    $types = explode(';',$contrat['types']);
    $debut = DateTime::createFromFormat('Y-m-d',$contrat['depart']);
    $fin = DateTime::createFromFormat('Y-m-d',$contrat['fin']);
?>

<label>Date de début : </label>
<input type="text" class="datepicker" id="1" style="display:inline-block;width:auto" value="" size="12" autocomplete="off">
<label>Date de fin : </label>
<input type="text" class="datepicker" id="2" style="display:inline-block;width:auto" value="" size="12" autocomplete="off">

<script>
    var debut = "<?php echo $debut->format('d/m/Y'); ?>";
    var fin = "<?php echo $fin->format('d/m/Y'); ?>";

    $('#1.datepicker').datepicker({
        startView: 1,
        defaultDate: debut,
        format: 'dd/mm/yyyy',
        autoclose: true
    }).datepicker('setDate',debut);

    $('#2.datepicker').datepicker({
        startView: 1,
        format: 'dd/mm/yyyy',
        autoclose: true
    }).datepicker('setDate',fin);

</script>

<table class="contrat table table-striped" id="<?php echo $contrat['id'] ?>"
   data_org_id="<?php echo $org->getId() ?>" width="100%">
    <thead>
        <th>Hotline</th>
        <th>Atelier/Sur site</th>
        <th>Régie</th>
        <th>Téléphonie</th>
    </thead>
    <tbody>
        <tr>
            <td><input type="checkbox" <?php if (in_array('1',$types)) echo 'checked'  ?>></td>
            <td><input type="checkbox" <?php if (in_array('2',$types)) echo 'checked'  ?>></td>
            <td><input type="checkbox" <?php if (in_array('3',$types)) echo 'checked'  ?>></td>
            <td><input type="checkbox" <?php if (in_array('4',$types)) echo 'checked'  ?>></td>
        </tr>
    </tbody>
</table>

<textarea name="commentaire" id="commentaire" cols="50"
                            placeholder="<?php echo __(
                            'Start writing your response here. Use canned responses from the drop-down above'
                            ); ?>"
                            rows="9" wrap="soft"
                            class="richtext"><?php echo $contrat['commentaire'] ?></textarea>

<button type="button" class="btn btn-success" id="insertOrUpdate">Valider</button>

<h3>Temps passé : </h3>
<!--<?php if (in_array('1',$types)) echo '<p><b>Hotline : </b> </p>' ?>
<?php if (in_array('1',$types)) echo '<p><b>Hotline : </b> </p>' ?>
<?php if (in_array('1',$types)) echo '<p><b>Hotline : </b> </p>' ?>
<?php if (in_array('1',$types)) echo '<p><b>Hotline : </b> </p>' ?>-->

<canvas id="tempsPasse" height="400"></canvas>

<?php
        } else {

?>
<label>Date de début : </label>
<input type="text" class="datepicker" id="1" style="display:inline-block;width:auto" value="" size="12" autocomplete="off">
<label>Date de fin : </label>
<input type="text" class="datepicker" id="2" style="display:inline-block;width:auto" value="" size="12" autocomplete="off">

<script>
    $('#1.datepicker').datepicker({
        startView: 1,
        format: 'dd/mm/yyyy',
        autoclose: true
    });

    $('#2.datepicker').datepicker({
        startView: 1,
        format: 'dd/mm/yyyy',
        autoclose: true
    });

</script>

<table class="contrat table table-striped" data_org_id="<?php echo $org->getId() ?>" width="100%">
    <thead>
        <th>Hotline</th>
        <th>Atelier/Sur site</th>
        <th>Régie</th>
        <th>Téléphonie</th>
    </thead>
    <tbody>
        <tr>
            <td><input type="checkbox"></td>
            <td><input type="checkbox"></td>
            <td><input type="checkbox"></td>
            <td><input type="checkbox"></td>
        </tr>
    </tbody>
</table>

<textarea name="commentaire" id="commentaire" cols="50"
                            placeholder="<?php echo __(
                            'Start writing your response here. Use canned responses from the drop-down above'
                            ); ?>"
                            rows="9" wrap="soft"
                            class="richtext"></textarea>

<button type="button" class="btn btn-success" id="insertOrUpdate">Valider</button>

<h3>Temps passé : </h3>

<canvas id="tempsPasse" height="400"></canvas>

<?php

    }
?>


<?php

    function sumHours($array){
        if(empty($array)){
            return 0;
        } else {
            $totalHours = 0;
            foreach($array as $horaire){
                $time1 = new DateTime($horaire['arrive_inter']);
                $time2 = new DateTime($horaire['depart_inter']);
                $interval = $time1->diff($time2);

                $totalHours += $time2->getTimestamp() - $time1->getTimestamp();
            }
            return intval($totalHours / 3600);
        }
    }

    $arrayHotline = Contrat::getInstance()->getTempsPasseContratType($org->getId(),"1;");
    $arrayAtelierSurSite = Contrat::getInstance()->getTempsPasseContratType($org->getId(),"2;");
    $arrayRegie = Contrat::getInstance()->getTempsPasseContratType($org->getId(),"3;");
    $arrayTelephonie = Contrat::getInstance()->getTempsPasseContratType($org->getId(),"4;");

    $tempsPasseHotline = sumHours($arrayHotline);
    $tempsPasseAtelierSurSite = sumHours($arrayAtelierSurSite);
    $tempsPasseRegie = sumHours($arrayRegie);
    $tempsPasseTelephonie = sumHours($arrayTelephonie);

?>
<script>
    var data = {
        labels: ["Hotline","Atelier-Sur site","Régie","Téléphonie"],
        datasets: [{
            label: "Temps passé" ,
            backgroundColor: "#FC9775" ,
            data: [<?php echo $tempsPasseHotline?>,
                  <?php echo $tempsPasseAtelierSurSite?>,
                  <?php echo $tempsPasseRegie?>,
                  <?php echo $tempsPasseTelephonie?>] ,
        }]
    };

    var ctx = $('#tempsPasse');
    ctx[0].width = $('.container').width()-70;
    new Chart(ctx, {
        type: 'bar'
        , data: data
        , options: {
            animation: {
                duration: 2000
            }
            , tooltips: {
                callbacks: {
                  label: function(tooltipItem, data) {
                    var datasetLabel = data.datasets[tooltipItem.datasetIndex].label || 'Other';
                    var hours = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                    return datasetLabel + ': ' + hours + ' H ';
                  }
                }
              }
            , responsive: false
            , maintainAspectRatio: false
            , scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                        , userCallback: function (label, index, labels) {
                            // N'afficher que les nombres entiers.
                            if (Math.floor(label) === label) {
                                return label;
                            }
                        }
                    , }
                                         }]
            , }
        , }
    , });

    /*$id,$id_org,$depart,$fin,$types,$commentaire*/
    $('#insertOrUpdate').click(function(){
        var types = '';
        var id = $('.contrat.table.table-striped').attr('id');
        var id_org = $('.contrat.table.table-striped').attr('data_org_id');
        var depart = $('#1.datepicker').val();
        var fin = $('#2.datepicker').val();
        var commentaire = $('#commentaire').val();

        var i = 1;
        $.each($('.contrat.table.table-striped tbody tr td'),function(){
            if($('input',this).is(':checked')){
                types = types + i + ";";
            }
            i += 1;
        });

        $.ajax({
           url : './Request/Contrat.php',
           type : 'POST',
           data:{
               insertOrUpdate:'',
               id: id,
               id_org: id_org,
               depart: depart,
               fin: fin,
               types:types,
               commentaire:commentaire
           },
           success : function(code_html, statut){ // code_html contient le HTML renvoyé
                location.reload();
           }
        });
    });

</script>

</div>
<div class="tab_content" id="users" style="display:none">
<?php
include STAFFINC_DIR . 'templates/users.tmpl.php';
?>
</div>
<div class="tab_content" id="tickets">
<?php
include STAFFINC_DIR . 'templates/tickets.tmpl.php';
?>
</div>

<div class="tab_content" id="notes" style="display:none">
<?php
$notes = QuickNote::forOrganization($org);
$create_note_url = 'orgs/'.$org->getId().'/note';
include STAFFINC_DIR . 'templates/notes.tmpl.php';
?>
</div>
</div>

<script type="text/javascript">
$(function() {
    $(document).on('click', 'a.org-action', function(e) {
        e.preventDefault();
        var url = 'ajax.php/'+$(this).attr('href').substr(1);
        $.dialog(url, [201, 204], function (xhr) {
            if (xhr.status == 204)
                window.location.href = 'orgs.php';
            else
                window.location.href = window.location.href;
         }, {
            onshow: function() { $('#org-search').focus(); }
         });
        return false;
    });
});
</script>
