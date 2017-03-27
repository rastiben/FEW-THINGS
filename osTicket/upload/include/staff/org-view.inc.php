<?php

/*require_once(SCP_DIR.'Request/Contrat.php');*/
require_once(SCP_DIR.'Request/Tickets.php');
require_once(INCLUDE_DIR.'class.contrat.php');

if(!defined('OSTSCPINC') || !$thisstaff || !isset($_REQUEST['id'])) die('Invalid path');

$orgsC = OrganisationCollection::getInstance();

$org = $orgsC->lookUpByName('411VDOC')[0];

$apiKey = "AIzaSyB4pINEbEV_CczgRAhMhIza1OAEzSJV6JA";

$tickets = TicketsInfos::getInstance()->ticket_org('3314');

?>
<h3>Profile : <?php echo $org->getName(); ?></h3>
<br>
<div class="col-md-12 block">
    <div class="col-md-2 logo">
        <img class="logo" src="../assets/default/images/company_building.png"/>
    </div>
    <div class="col-md-10 infos">
        <b><div class="col-md-12 group">
            <div class="col-md-3">
                <img class="icon" src="../assets/default/images/location.png"/>
                <p class="infoLabel">Adresse :</p>
            </div>
            <div class="col-md-9">
               <p> <?php echo $org->getAddress() . " " . $org->getComplement(); ?><br>
                <?php echo $org->getCP() . " " . $org->getCity(); ?></p>
            </div>
        </div>
        <div class="col-md-12 group">
            <div class="col-md-3">
                <img class="icon" src="../assets/default/images/tel.png"/>
                <p class="infoLabel">Numéro de téléphone :</p>
            </div>
            <div class="col-md-9">
                <p> <?php echo $org->getPhone(); ?></p>
            </div>
        </div>
        <div class="col-md-12 group">
            <div class="col-md-3">
                <img class="icon" src="../assets/default/images/website.png"/>
                <p class="infoLabel">Site Web :</p>
            </div>
            <div class="col-md-9">
                <p> <?php echo $org->getWebSite(); ?></p>
            </div>
        </div></b>
    </div>
</div>

<div class="col-md-12 block">
    <?php
    include STAFFINC_DIR . 'templates/tickets.tmpl.php';
    ?>
</div>

<div class="col-md-12 block">
    <?php
    include STAFFINC_DIR . 'templates/users.tmpl.php';
    ?>
</div>

<script async defer
      src="https://maps.googleapis.com/maps/api/js?key=<?php echo $apiKey; ?>">
    </script>

<!--MAP-->
<div style="height:400px;width:800px;margin:auto" id="map"></div>

<br>
<div class="clear"></div>

<!--<ul class="clean tabs" id="orgtabs">
    <li class="active"><a href="#users"><i
    class="icon-user"></i>&nbsp;<?php echo __('Users'); ?></a></li>
    <li><a href="#tickets"><i
    class="icon-list-alt"></i>&nbsp;<?php echo __('Tickets'); ?></a></li>
    <li><a href="#notes"><i
    class="icon-pushpin"></i>&nbsp;<?php echo __('Notes'); ?></a></li>
    <li><a href="#contrat">&nbsp;<?php echo __('Contrat'); ?></a></li>
</ul>
<div id="orgtabs_container">-->



<div class="tab_content" id="contrat" style="display:none">

<?php

    //$contrat = Contrat::getInstance()->getContrat($org->getId());
    $contratC = contratCollection::getInstance();
    $contrat = $contratC->lookUpById($org->getId());
    print_r($contrat);

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
<?php if (in_array('1',$types)) echo '<p><b>Hotline : </b> </p>' ?>
<?php if (in_array('1',$types)) echo '<p><b>Hotline : </b> </p>' ?>
<?php if (in_array('1',$types)) echo '<p><b>Hotline : </b> </p>' ?>
<?php if (in_array('1',$types)) echo '<p><b>Hotline : </b> </p>' ?>

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

    /*function sumHours($array){
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
    $tempsPasseTelephonie = sumHours($arrayTelephonie);*/

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

    /*$('#insertOrUpdate').click(function(){
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
    });*/

</script>

</div>

<div class="tab_content" id="notes" style="display:none">
<?php
/*$notes = QuickNote::forOrganization($org);
$create_note_url = 'orgs/'.$org->getId().'/note';
include STAFFINC_DIR . 'templates/notes.tmpl.php';*/
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

    var map = null;
    var address = "<?php echo str_replace(" ","+",$org->getComplement() . " " . $org->getAddress() . " " . $org->getCP()) ?>";

    $.ajax({
        method:"GET",
        url:"https://maps.googleapis.com/maps/api/geocode/json?address="+address+"&key=<?php echo $apiKey; ?>",
        success: function(data){
            //if no result test without address or without complement.
            //alert(data.results[0].geometry.location);
            var location = data.results[0].geometry.location;
            var LatLng = {lat: location.lat, lng: location.lng};

            map = new google.maps.Map(document.getElementById('map'), {
                center: LatLng,
                zoom: 12
            });
            var marker = new google.maps.Marker({
                position: LatLng,
                map: map
            });
        }
    });
</script>
