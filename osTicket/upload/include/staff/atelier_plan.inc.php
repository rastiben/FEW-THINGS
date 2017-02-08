<?php

?>
    <div class="plan">
        <h1>Plan de l'atelier : </h1>
        <div class="atelier">
            <div class="bureau" id="un"></div>
            <div class="bureau" id="deux"></div>
            <div class="bureau" id="trois"></div>
            <div class="portable" id="un"></div>
            <div class="portable" id="deux"></div>
            <div class="portable" id="trois"></div>
            <div class="mur" id="un"></div>
            <div class="mur" id="deux"></div>
            <div class="mur" id="trois"></div>
            <div class="mur" id="quatre"></div>
            <div class="serveur" id="un"></div>
            <div class="serveur" id="deux"></div>
            <div class="serveur" id="trois"></div>
            <div class="serveur" id="quatre"></div>
            <div class="serveur" id="cinq"></div>
            <div class="serveur" id="six"></div>
        </div>

            <div class="modal fade" id="fichesModal">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <p></p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-primary"></button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"></button>
                  </div>
                </div>
              </div>
            </div>

        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.1/angular.min.js"></script>

        <script type="text/javascript">

        $(function() {

            //Initiate
            $(document).off('click', '.atelier div');

            //Gestion de l'atelier
            $(document).on('click', '.atelier div', function(e) {
                var planche = $(this);
                $('.modal-title').text((planche.attr('class') + ' ' + planche.attr('id')).replace(/\b[a-z]/g,function(f){return f.toUpperCase();}));
                $('#fichesModal').modal('toggle');
            });

        });

        </script>
