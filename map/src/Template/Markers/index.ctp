<html>
    <head>
        <title></title>
    </head>
    <body>


        <link rel="stylesheet" href="./css/leaflet.css">
        <link rel="stylesheet" href="./css/app.css">
        <link rel="stylesheet" href="./css/Control.Geocoder.css" />
        <link rel="stylesheet" href="./css/leaflet.contextmenu.min.css"/>
        <link rel="stylesheet" href="./css/bootstrap.min.css"/>
        <link rel="stylesheet" href="./css/easy-button.css"/>


        <!--<div class="filtres col-md-8 col-md-offset-2">
            <div class="filtre active col-md-6">En cours de construction</div>
            <div class="filtre col-md-6">Finalisé</div>
        </div>-->

        <div class="container col-md-12 app">
            <div id="map">
            </div>
            <div class="col-md-4 form">
                <form action="Markers/add.json" id="addMarker" method="post">
                  <div class="form-group">
                    <label for="lat">Latitude</label>
                    <input type="text" name="lat" id="lat" class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="lng">Longitude</label>
                    <input type="text" name="lng" id="lng" class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="constructeur">Constructeur</label>
                    <select name="constructeur" id="constructeur" class="form-control">
                        <option>Maison d'aujourd'hui</option>
                        <option>Demeures & Cottages</option>
                        <option>Maison sweet</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="client">Client</label>
                    <input type="text" name="client" id="client" class="form-control">
                  </div>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input id='dispo' type='checkbox' value='1' name='dispo' class="form-check-input">
                      <input id='dispoHidden' type='hidden' value='0' name='dispo'>
                      Maison Finalisée
                    </label>
                  </div>
                  <button type="submit" id="submit" class="btn btn-success pull-right">Valider</button>
                  <button type="reset" style="margin-right:15px" class="btn btn-danger pull-right" id="cancel">Annuler</button>
                </form>
            </div>
        </div>




        <script src="./js/leaflet.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.1/angular.min.js"></script>
        <script src="./js/angular-leaflet-directive.min.js"></script>
        <script src="./js/Control.Geocoder.js"></script>
        <script src="./js/leaflet.contextmenu.min.js"></script>
        <script src="./js/jquery-3.1.1.min.js"></script>
        <script src="./js/bootstrap.min.js"></script>
        <script src="./js/easy-button.js"></script>


        <script>

            var blueIcon = new L.Icon({
              iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-blue.png',
              shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
              iconSize: [25, 41],
              iconAnchor: [12, 41],
              popupAnchor: [1, -34],
              shadowSize: [41, 41]
            });

            var purpleIcon = new L.Icon({
              iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-violet.png',
              shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
              iconSize: [25, 41],
              iconAnchor: [12, 41],
              popupAnchor: [1, -34],
              shadowSize: [41, 41]
            });

            var orangeIcon = new L.Icon({
              iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-orange.png',
              shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
              iconSize: [25, 41],
              iconAnchor: [12, 41],
              popupAnchor: [1, -34],
              shadowSize: [41, 41]
            });

            
            
            var markers = [];
            var layer = undefined;
            
            //Initialisation de la carte.
            var map = L.map('map', {
                contextmenu: true,
                contextmenuWidth: 180,
                contextmenuItems: [{
                    text: 'Ajouter un marker',
                    callback: showCoordinates
                }]
            }).setView(new L.LatLng(46.5833, 0.3333), 9);

            
            /*L.easyButton('<img style="margin-top: -3px;" src="https://image.flaticon.com/icons/svg/263/263115.svg"/>', function(){
              filtre(false);
            }).addTo(map);
            
            L.easyButton('<img style="margin-top: -3px;" src="https://image.flaticon.com/icons/svg/204/204112.svg"/>', function(){
              filtre(true);
            }).addTo(map);*/
            
            
            var toggle = L.easyButton({
              states: [{
                stateName: 'home',
                title: 'Maison finalisée',
                icon: 'glyphicon glyphicon-home',
                onClick: function(control) {
                    filtre(false);
                    control.state('contstruct');
                }
              }, {
                stateName: 'contstruct',
                title: 'Maison en cours de construction',
                icon: 'glyphicon glyphicon-wrench',
                onClick: function(control) {
                    filtre(true);
                    control.state('home');
                }
              }]
            });
            toggle.addTo(map);
            
            $.ajax({
                url: "Markers.json",
                type: "GET",
                success: function(data) {
                    var temp = [];
                        
                    $.each(data.markers,function(key,value){
                        
                        var icon = undefined;
                        switch(value.constructeur){
                            case "Maison d'aujourd'hui":
                                icon = blueIcon;
                                break;
                            case "Demeures & Cottages":
                                icon = purpleIcon;
                                break;
                            case "Maison sweet":
                                icon = orangeIcon;
                                break;
                        }
                        
                        if(value.dispo)
                            temp.push(L.marker([value.lat, value.lng], {icon: icon}).on('click', onClick));
                        markers.push(value);
                    });
                    
                    layer = new L.FeatureGroup(temp).addTo(map);
                }
            });
            
            function onClick(e) {
                var arr = $.grep(markers, function(value,key) {
                  return value.lat == e.latlng.lat && value.lng == e.latlng.lng;
                });
                displayForm(arr[0]);
            }
            

            //Ajout du layout
            L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            //Ajout de la recherche
            L.Control.geocoder({
                placeholder: "Rechercher..."
                ,errorMessage: "Aucun résultat"
            }).addTo(map);

            //CallBack affiche des coordonnées
            function showCoordinates (e) {
                //alert(e.latlng);
                displayForm();

                L.marker([e.latlng.lat, e.latlng.lng]).addTo(map)
                .bindPopup('Remplissez le formulaire')
                .openPopup();

                $('.form #lat').val(e.latlng.lat);
                $('.form #lng').val(e.latlng.lng);

            }

            var displayForm = function(obj = undefined){
                
                $("#submit").html('Valider');
                $("#lat").val('');
                $("#lat").prop('disabled',false);
                $("#lng").val('');
                $("#lng").prop('disabled',false);
                $("#constructeur").val('');
                $("#client").val('');
                $("#dispo").prop('checked',false);
                $('#addMarker').removeAttr('data-id');
                
                if(obj != undefined){
                    $("#lat").val(obj.lat);
                    $("#lat").prop('disabled',true);
                    $("#lng").val(obj.lng);
                    $("#lng").prop('disabled',true);
                    $("#constructeur").val(obj.constructeur);
                    $("#client").val(obj.client);
                    $("#dispo").prop('checked',obj.dispo);
                    $("#submit").html('Modifier');
                    $('#addMarker').attr('data-id',obj.id);
                }
                
                $('.form').css('left','0px');
                /*$('.form').css('display','block');
                $('#map').removeClass('col-md-8 col-md-offset-2').addClass('col-md-7');
                $('.form').removeClass('col-md-4').addClass('col-md-5');
                
                $('.filtres').removeClass('col-md-8 col-md-offset-2').addClass('col-md-7');*/
            }
            
            var hideForm = function(){
                $('.form').css('left','-370px');
                /*$('.form').css('display','none');
                $('#map').removeClass('col-md-7').addClass('col-md-8 col-md-offset-2');
                $('.form').removeClass('col-md-5').addClass('col-md-5col-md-4');
                
                $('.filtres').removeClass('col-md-7').addClass('col-md-8 col-md-offset-2');*/
            }

            $('#addMarker').on('submit', function(e) {
                e.preventDefault();
                
                var $this = $(this);
                
                if($("#dispo").prop('checked')) {
                    $('#dispoHidden').prop('disabled',true);
                }
                
                var url = undefined;
                var dataId = $this.attr('data-id');
                url = dataId == undefined ? $this.attr('action') : "Markers\\edit\\"+dataId+".json";
                
                $.ajax({
                    url: url,
                    type: $this.attr('method'),
                    data: $this.serialize(),
                    success: function(html) {
                        //alert(html);
                        
                        if(dataId != undefined){
                            var obj = $.grep(markers, function(value,key) {
                              return value.id == dataId;
                            })[0];
                            obj.lat = $('#lat').val();
                            obj.lng = $('#lng').val();
                            obj.constructeur = $('#constructeur').val();
                            obj.client = $('#client').val();
                            obj.dispo = $('#dispo').prop('checked');
                        }
                        
                        hideForm();
                    }
                });
            });
            
            $('#cancel').click(function(){
               hideForm(); 
            });
            
            var filtre = function(filtre){
                var $this = $(this);
                $this.siblings().removeClass('active');
                $this.addClass("active");
                
                map.removeLayer(layer);
                
                var temp = [];
                        
                $.each(markers,function(key,value){
                    
                     var icon = undefined;
                        switch(value.constructeur){
                            case "Maison d'aujourd'hui":
                                icon = blueIcon;
                                break;
                            case "Demeures & Cottages":
                                icon = purpleIcon;
                                break;
                            case "Maison sweet":
                                icon = orangeIcon;
                                break;
                        }
                    
                    if(value.dispo == filtre)
                        temp.push(L.marker([value.lat, value.lng], {icon : icon}).on('click', onClick));
                });
                    
                layer = new L.FeatureGroup(temp).addTo(map);
                
            };

        </script>
    </body>
</html>
