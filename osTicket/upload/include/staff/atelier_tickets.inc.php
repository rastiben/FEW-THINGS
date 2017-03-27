<?php

//require_once(SCP_DIR . 'Request/Tickets.php');
//$atelier = TicketsInfos::getInstance()->atelier_tickets();

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

//$orgsC = OrganisationCollection::getInstance();
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
            <li class="col-md-5"><p class="active">Liste completes</p></li>
            <li class="col-md-5"><p>Entrées</p></li>
            <li class="col-md-5"><p>Planche</p></li>
            <li class="col-md-5"><p>Sorties</p></li>
            <li class="col-md-5"><p>RMA</p></li>
       </ul>
   </div>
    <table class="list atelierT" border="0" cellspacing="1" cellpadding="2" width="100%">
        <thead>
            <th>Ticket</th>
            <th>Organisation</th>
            <th>Type</th>
            <th>Priorité</th>
        </thead>
        <tbody> </tbody>
        <tfoot> </tfoot>
    </table>

<script>
    $(document).ready(function(){
        var planches = new Planche(function(contenues){
            $(contenues).each(function(number,obj){
                addContenuInListe(obj);
            });
        });
        var addContenuInListe = function(obj){
            $('.list.atelierT tbody').append('<tr class="'+obj.etat+'"><td><a class="Icon Ticket no-pjax" href="./tickets.php?id='+obj.ticket_id+'">'+obj.number+'</a></td><td><a class="no-pjax" href="./orgs.php?id='+obj.org_id+'">'+obj.org_name+'</a></td><td>'+obj.getType()+'</td><td>'+obj.priority+'</td></tr>');
        }
    });

    $('.filter li').click(function(){
        $('p',$(this).siblings()).removeClass('active');
        $('p',$(this)).addClass('active');
        //n'afficher que les tr
        $('.list.atelierT tbody tr').css('display','none');
        switch($('p',$(this)).text()){
            case 'Entrées':
                $('.list.atelierT tbody tr.Entrée').css('display','table-row');
                break;
            case 'Planche':
                $('.list.atelierT tbody tr.Planche').css('display','table-row');
                break;
            case 'Sorties':
                $('.list.atelierT tbody tr.Sortie').css('display','table-row');
                break;
            case 'RMA':
                $('.list.atelierT tbody tr.RMA').css('display','table-row');
                break;
            default:
                $('.list.atelierT tbody tr').css('display','table-row');
                break;
        }
    });

</script>


</div>

