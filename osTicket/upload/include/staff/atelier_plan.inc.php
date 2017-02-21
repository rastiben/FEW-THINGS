<?php
    require_once('./Request/GetInfos.php');
    require_once('./Request/Atelier.php');

?>

    <div class="plan col-md-12">
        <h1 class="col-md-12">Plan de l'atelier : </h1>
        <div class="atelier col-md-9">
            <div class="img">
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
                <img src="../assets/atelier/atelier.png"/>
            </div>
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

            <div class="modal fade" id="fichesModal" data_planche="" data_id_contenu="" data_staff="<?php echo $thisstaff->getId() ?>">
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
                        <table class="list atelierT" border="0" cellspacing="1" cellpadding="2" width="100%">
                            <thead>
                                <th>ID du Ticket</th>
                                <th>Type</th>
                                <th>Etat</th>
                                <th>Affecter</th>
                             </thead>
                             <tbody>

                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>
                      </div>
                    </div>
                    <div class="container fiche">
                        <div class="retour title">
                            <h3></h3>
                            <select class="custom-select changeState">
                               <option>Entrées</option>
                               <option>Planche</option>
                               <option>Sorties</option>
                               <option>RMA</option>
                           </select>
                        </div>
                        <div class="repaTmpl" style="display:none">

                           <div class="col-md-12">
                                <div class="inputField col-md-6">
                                    <input id="typeAppareil" required>
                                    <label for="typeAppareil">Type d'appareil</label>
                                </div>

                                <div class="inputField col-md-6">
                                    <input id="motDePasse" required>
                                    <label for="motDePasse">MDP</label>
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
                                    <textarea id="modele" required></textarea>
                                    <label for="modele">Modèle</label>
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
                    <button type="button" class="btn btn-secondary validerOuEnregistrer">Valider</button>
                    <button type="button" class="btn btn-secondary validerOuEnregistrer" style="display:none">Retour</button>
                  </div>
                </div>
              </div>
            </div>
        </div>


      <script type="text/javascript">

        //$(function() {

            var planches = new Planche();

            //Initiate
            /*$(document).off('click', '.atelier div');
            $(document).off('click', '.addContenu');
            $(document).off('click', '.contenu img');
            $(document).off('click', '.validerOuEnregistrer');
            $(document).off('hidden.bs.modal', '.modal');*/

            //Gestion de l'atelier
            $(document).on('click', '.atelier div div', function(e) {
                var planche = $(this);

                planches.getContenues(function(contenues){
                    /*INIT*/
                    $('.modal-body .container.home').show();
                    $('.modal-body .container.fiche').hide();

                    $('.modal-title').text((planche.attr('class') + ' ' + planche.attr('id')).replace(/\b[a-z]/g,function(f){return f.toUpperCase();}));
                    $('#fichesModal').attr('data_planche',planche.attr('data_planche'));


                    var contenu = planches.getPlanche(planche.attr('data_planche'));
                    $('.modal-body .contenu').remove();

                    $(contenu).each(function(number,obj){
                            addContenuInPlanche(obj.getId(),obj.getType());
                    });

                    $('#fichesModal').modal({backdrop: 'static', keyboard: false});

                    $('.list.atelierT tbody').empty();
                    $(contenues).each(function(number,obj){
                        addContenuInListe(obj);
                    });
                });
            });

            //Ajouter une prepa/repa
            $(document).on('click','.addContenu',function(){
                var id = $(this).attr('id');
                var planche = $('#fichesModal').attr('data_planche');
                var tr = $(this).closest('tr');

                planches.changeState(id, "Planche");
                planches.affectContenu(id,planche,function(type){
                    addContenuInPlanche(id,type);
                    tr.remove();
                });

            });

            $(document).on('click','.contenu img.remove',function(){
                var contenu = $(this).closest('.contenu');
                var obj = planches.getContenu(contenu.attr('id'));
                obj = obj[0];

                planches.changeState(obj.getId(), "Entrée");
                planches.affectContenu(obj.getId(),null,function(){
                    addContenuInListe(obj);
                    contenu.remove();
                });
            });

            $(document).on('click','.contenu .finish img',function(){

                $(this).parent().css({
                        'border': '1px solid #28B463',
                        'border-right': '0px',
                        'border-radius': '20px 0px 0px 20px'
                });
                $(this).parent().animate({
                    width: '130px'
                },{
                    duration:300,
                    queue:false
                });

                $(this).animate({
                    left:'0px'
                },{
                    duration:300,
                    queue:false
                });
                $('.prepa .finish h3').animate({
                    right:'12px'
                },{
                    duration:350,
                    queue:false
                });
            });

            $(document).on('click','.contenu .finish h3',function(){
                var contenu = $(this).closest('.contenu');
                var obj = planches.getContenu(contenu.attr('id'));
                obj = obj[0];
                planches.changeState(obj.getId(),'Terminé');
                planches.affectContenu(obj.getId(),null,function(){
                    contenu.remove();
                });
            });

            //Affichage de la fiche
            $(document).on('click','.contenu img.computer',function(){

                var id = $(this).closest('.contenu').attr('id');
                var data = planches.getContenu(id);
                data = data[0];

                //Récupération de la valeur d'une checkbox
                function getValue(value){
                    return !!+value.substr(1,value.indexOf(':')-1);
                }

                //Récupération du staff ayant coché
                function getStaffId(value){
                    return value.substring(value.indexOf(':')+1,value.indexOf('}'));
                    //return value.substr(value.length-2,1);
                }

                //AFFECTATION DES VALEURS
                var type = "";
                if(data.getType() == 'prepa'){
                    type = "Fiche de préparation";
                    if(data['contenu'].PEC != "PEC"){
                        $('#nomDuPoste').val(data['contenu'].VD);
                        $('#modele').val(data['contenu'].modele);
                        $('#etiquetage').attr('checked',data['contenu'].etiquetage == "1" ? true:false);
                        $('#dossierSAV').attr('checked',data['contenu'].dossierSAV == "1" ? true:false);
                        $('#septZip').prop('checked', getValue(data['contenu'].septZip))
                            .attr('data_staff', getStaffId(data['contenu'].septZip) == "null" ? null:getStaffId(data['contenu'].septZip));
                        $('#acrobat').prop('checked', getValue(data['contenu'].acrobat))
                            .attr('data_staff', getStaffId(data['contenu'].acrobat) == "null" ? null:getStaffId(data['contenu'].acrobat));
                        $('#flash').prop('checked', getValue(data['contenu'].flash))
                            .attr('data_staff', getStaffId(data['contenu'].flash) == "null" ? null:getStaffId(data['contenu'].flash));
                        $('#java').prop('checked', getValue(data['contenu'].java))
                            .attr('data_staff', getStaffId(data['contenu'].java) == "null" ? null:getStaffId(data['contenu'].java));
                        $('#pdf').prop('checked', getValue(data['contenu'].pdf))
                            .attr('data_staff', getStaffId(data['contenu'].pdf) == "null" ? null:getStaffId(data['contenu'].pdf));
                        $('#autre').val(data['contenu'].autre);
                        $('#type').val(data['contenu'].type);
                        $('#userAccount').val(data['contenu'].userAccount);
                        $('#mdp').val(data['contenu'].mdp);
                        $('#activation').prop('checked', getValue(data['contenu'].activation))
                            .attr('data_staff', getStaffId(data['contenu'].activation) == "null" ? null:getStaffId(data['contenu'].activation));
                        $('#uninstall').attr('checked', data['contenu'].uninstall == "1" ? true:false);
                        $('#maj').attr('checked', data['contenu'].maj == "1" ? true:false);
                        $('#register').attr('checked', data['contenu'].register == "1" ? true:false);
                        $('#verifActivation').attr('checked', data['contenu'].verifActivation == "1" ? true:false);
                        $('#divers').val(data['contenu'].divers)
                    }
                } else {
                    type = "Fiche de réparation";
                    if(data['contenu'].PEC != "PEC"){
                        $('#typeAppareil').val(data['contenu'].typeAppareil);
                        $('#motDePasse').val(data['contenu'].motDePasse);
                        $('#description').val(data['contenu'].description);
                        $('#comTech').val(data['contenu'].comTech);
                        $('#tempsInter').val(data['contenu'].tempsInter);
                        $('#dateMiseADisposition').val(data['contenu'].dateMiseADisposition);
                        $('#visaClient').val(data['contenu'].visaClient);
                        $('#visaTech').val(data['contenu'].visaTech);
                        $('#intervention').val(data['contenu'].intervention);
                        $('#tempsPasse').val(data['contenu'].tempsPasse);
                        $('#svisaTech').val(data['contenu'].svisaTech);
                        $('#comIntervention').val(data['contenu'].comIntervention);
                        $('#verifClient').prop('checked',data['contenu'].verifClient == "1" ? true:false);
                        $('#dateReprise').val(data['contenu'].dateReprise);
                    }
                }

                //CHANGEMENT DES CHOIX DE LA SELECT
                if(type=="Fiche de réparation"){
                    $('.changeState').val(data.getEtat());
                    $('.changeState').css('display','block');
                }
                else{
                    $('.changeState').css('display','none');
                }

                autosize($('#modele'));
                //CHANGEMENT DU TITRE
                $('.retour.title h3').html(type);

                //CHANGEMENT DES INFOS
                $('#fichesModal').attr('data_id_contenu',id);

                /*GESTION DU CONTENU*/
                $('.container.fiche .repaTmpl').css('display',type=="Fiche de réparation"?"block":"none");
                $('.container.fiche .prepaTmpl').css('display',type=="Fiche de préparation"?"block":"none");

                //fade left out.
                $('.modal-body .container.home').hide("slide", { direction: "left" }, 600);
                //fade right in
                $('.modal-body .container.fiche').css('display','block');
                $('.modal-body .container.fiche').animate({
                    right : 0,
                    left : 0
                },{
                    duration:600,
                    queue:false,
                    complete: function(){
                    $('.modal-body .container.fiche').css('position','relative');
                    $('.modal-body .container.fiche').css('margin-top','0px');
                }
                });


                $('.modal-body').animate({
                    height: $('.container.fiche').height()+30
                },{
                    duration:600,
                    queue:false,
                    complete: function(){
                        $('.modal-body').css('height','auto');
                    }
                });

                //CHANGER LE BOUTON POUR ENREGISTRER
                $('.validerOuEnregistrer').first().text('Enregistrer');
                $('.validerOuEnregistrer').last().css('display','inline-block');
            });

            function switchModal() {
                    $('.modal-body').css('height',$('.modal-body .container.fiche').height()+30);
                    $('.modal-body .container.fiche').css('position','absolute');
                    $('.modal-body .container.fiche').css('margin-top','15px');
                    $('.modal-body .container.fiche').animate({
                        right : '-100%',
                        left : '100%'
                    },{
                        duration:600,
                        queue:false,
                        complete :function(){
                            $('.modal-body .container.fiche').css('display','none');
                        }
                    });

                    $('.modal-body').animate({
                        height: $('.container.home').height()+30
                    },{
                        duration:600,
                        queue:false,
                        complete:function(){
                            $('.modal-body').css('height','auto');
                        }
                    });

                    //fade right in
                    $('.modal-body .container.home').show("slide", { direction: "left" }, 600);

                    //CHANGER LE BOUTON POUR ENREGISTRER
                    $('.validerOuEnregistrer').first().text('Valider');
                    $('.validerOuEnregistrer').last().css('display','none');
                }

            //Retour sur la planche.
            $(document).on('click','.validerOuEnregistrer',function(){

                if($(this).text() == 'Enregistrer'){
                    //fade right out
                    if($('.retour h3').text() == 'Fiche de préparation'){
                        //Recuperation des champs
                        var id_contenu = $('#fichesModal').attr('data_id_contenu');
                        var vd = $('#nomDuPoste').val();
                        var modele = $('#modele').val();
                        var etiquetage = $('#etiquetage').is(':checked') ? '1' : '0';
                        var dossierSAV = $('#dossierSAV').is(':checked') ? '1' : '0';
                        var septZip = $('#septZip').is(':checked') ? '{1:'+ $('#septZip').attr('data_staff') +'}' : '{0:null}';
                        var acrobat = $('#acrobat').is(':checked') ? '{1:'+ $('#acrobat').attr('data_staff') +'}' : '{0:null}';
                        var flash = $('#flash').is(':checked') ? '{1:'+ $('#flash').attr('data_staff') +'}' : '{0:null}';
                        var java = $('#java').is(':checked') ? '{1:'+ $('#java').attr('data_staff') +'}' : '{0:null}';
                        var pdf = $('#pdf').is(':checked') ? '{1:'+ $('#pdf').attr('data_staff') +'}' : '{0:null}';
                        var autre = $('#autre').val();
                        var type = $('#type').val();
                        var userAccount = $('#userAccount').val();
                        var mdp = $('#mdp').val();
                        var activation = $('#activation').is(':checked') ? '{1:'+ $('#activation').attr('data_staff') +'}' : '{0:null}';
                        var uninstall = $('#uninstall').is(':checked') ? '1' : '0';
                        var maj = $('#maj').is(':checked') ? '1' : '0';
                        var register = $('#register').is(':checked') ? '1' : '0';
                        var verifActivation = $('#verifActivation').is(':checked') ? '1' : '0';
                        var divers = $('#divers').val();

                        //Insertion ou mise a jour
                        planches.insertOfUpdatePrepa(id_contenu
                                                    ,$('.modal').attr('data_planche')
                                                    ,vd
                                                    ,modele
                                                    ,etiquetage
                                                    ,dossierSAV
                                                    ,septZip
                                                    ,acrobat
                                                    ,flash
                                                    ,java
                                                    ,pdf
                                                    ,autre
                                                    ,type
                                                    ,userAccount
                                                    ,mdp
                                                    ,activation
                                                    ,uninstall
                                                    ,maj
                                                    ,register
                                                    ,verifActivation
                                                    ,divers);

                    } else {
                        var id_contenu = $('#fichesModal').attr('data_id_contenu');
                        var typeAppareil = $('#typeAppareil').val();
                        var motDePasse = $('#motDePasse').val();
                        var description = $('#description').val();
                        var comTech = $('#comTech').val();
                        var tempsInter = $('#tempsInter').val();
                        var dateMiseADisposition = $('#dateMiseADisposition').val();
                        var visaClient = $('#visaClient').val();
                        var visaTech = $('#visaTech').val();
                        var intervention = $('#intervention').val();
                        var tempsPasse = $('#tempsPasse').val();
                        var svisaTech = $('#svisaTech').val();
                        var comIntervention = $('#comIntervention').val();
                        var verifClient = $('#verifClient').is(':checked') ? '1' : '0';
                        var dateReprise = $('#dateReprise').val();

                        planches.insertOfUpdateRepa(id_contenu
                                                    ,$('.modal').attr('data_planche')
                                                    ,typeAppareil
                                                    ,motDePasse
                                                    ,description
                                                    ,comTech
                                                    ,tempsInter
                                                    ,dateMiseADisposition
                                                    ,visaClient
                                                    ,visaTech
                                                    ,intervention
                                                    ,tempsPasse
                                                    ,svisaTech
                                                    ,comIntervention
                                                    ,verifClient
                                                    ,dateReprise);

                    }
                    switchModal();

                } else if($(this).text() == 'Retour') {
                    switchModal();
                } else {

                    $('#fichesModal').modal('toggle');
                }
            });

            $(document).on('hidden.bs.modal','.modal', function () {
                //INIT
                $('.modal-body .container.fiche').css('right','-100%');
                $('.modal-body .container.fiche').css('left','100%');
                $('.modal-body .container.fiche').css('display','none');
            });


            /*ASSIGNATION DU VISA*/
            $(document).on('click','.prepaTmpl input[type="checkbox"]',function(){
                $(this).attr('data_staff',$('.modal').attr('data_staff'));
            });

              //CHANGER L'ETAT
            $(document).on('change','.changeState',function(){
                var id = $('.modal').attr('data_id_contenu');
                var state = $(':selected',this).val();
                planches.changeState(id,state);

                //SWITCH CONTENT
                if(state != "Planche"){
                    var contenu = planches.getContenu(id)[0];
                    planches.affectContenu(id,null,function(){
                        $('#'+id+'.contenu').remove();
                        addContenuInListe(contenu);
                        switchModal();
                    });
                }
            });

            var addContenuInPlanche = function(id,type){
                $('.modal-body div:first').prepend('<div class="col-md-3 contenu" id="'+id+'">'+
                    '<div class="prepa">'+
                    '<div class="finish"><img src="../assets/default/images/finish.png"><h3>Valider</h3></div>'+
                    '<img class="remove" src="../assets/default/images/remove.png">'+
                    '<img class="computer" src="../assets/default/images/computer.png">'+
                    '<input value="'+ (type == "prepa"  ? "VD _ _ _ _" : "REPA") +'"/>'+
                    '</div>'+
                    '</div>');
            }

            var addContenuInListe = function(obj){
                $('.list.atelierT tbody').append('<tr><td>'+obj.getId()+'</td><td>'+obj.getType()+'</td><td>'+obj.getEtat()+'</td><td><button class="btn btn-success addContenu" id="'+ obj.getId() +'" >Affecter</button></td></tr>');
            }


        //});

        </script>
