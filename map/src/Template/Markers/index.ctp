<html>
    <head>
        <title></title>
    </head>
    <body ng-app="mapAngular">


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

        <div class="container col-md-12 app" ng-controller="mapController">
            <leaflet data-show-info-form="showInfoForm" data-switch-info-form="switchInfoForm()"></leaflet>
            <div ng-class="{'col-md-4 form active': showInfoForm,'col-md-4 form': !showInfoForm}" >
                <form id="addMarker" method="post">
                  <div class="form-group">
                    <label for="lat">Latitude</label>
                    <input type="text" name="lat" ng-model="marker.lat" id="lat" class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="lng">Longitude</label>
                    <input type="text" name="lng" ng-model="marker.lng" id="lng" class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="constructeur">Constructeur</label>
                    <select ng-model="marker.constructeur" name="constructeur" id="constructeur" class="form-control">
                        <option>Maison d'aujourd'hui</option>
                        <option>Demeures & Cottages</option>
                        <option>Maison sweet</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="client">Client</label>
                    <input type="text" ng-model="marker.client" name="client" id="client" class="form-control">
                  </div>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input id='dispo' type='checkbox' ng-model="marker.dispo" name='dispo' class="form-check-input">
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
        <script src="https://code.angularjs.org/1.6.1/angular-resource.min.js"></script>
        <script src="./js/angular-leaflet-directive.min.js"></script>
        <script src="./js/Control.Geocoder.js"></script>
        <script src="./js/leaflet.contextmenu.min.js"></script>
        <script src="./js/jquery-3.1.1.min.js"></script>
        <script src="./js/bootstrap.min.js"></script>
        <script src="./js/easy-button.js"></script>
        <script src="./js/app.js"></script>

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

            
            //Initialisation de la carte.

            
            /*L.easyButton('<img style="margin-top: -3px;" src="https://image.flaticon.com/icons/svg/263/263115.svg"/>', function(){
              filtre(false);
            }).addTo(map);
            
            L.easyButton('<img style="margin-top: -3px;" src="https://image.flaticon.com/icons/svg/204/204112.svg"/>', function(){
              filtre(true);
            }).addTo(map);*/
            

            

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
        
        </script>
    </body>
</html>
