<div ng-controller="contratCtrl">

  <modal class="contratModals" lolo="modal1" data-header="Ajout d'un contrat" data-ng-click-right-button="save(contrat)"></modal>
  <a href="#{{modal1}}" role="button" class="btn btn-success" data-toggle="modal">Ajouter un contrat</a>

  <div class="block">
    <table class="table table-striped">
      <thead>
        <th>Code</th>
        <th>Organisation</th>
        <th>Etat</th>
        <th>Date de debut</th>
        <th>Date de fin</th>
        <th>Type</th>
        <th>Date d'établissement</th>
      </thead>
      <tbody>
        <tr ng-repeat="contrat in contrats">
          <td>{{contrat.code}}</td>
          <td>{{contrat.org}}</td>
          <td>{{contrat.etat}}</td>
          <td>{{contrat.date_debut | mFormat:'DD/MM/YYYY'}}</td>
          <td>{{contrat.date_fin | mFormat:'DD/MM/YYYY'}}</td>
          <td>{{contrat.type}}</td>
          <td>{{contrat.created | mFormat:'DD/MM/YYYY'}}</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<script src="../js/angular-resource.min.js"></script>
<script src="./js/moment.js"></script>
<script src="./js/contrats.js"></script>

<script>

$(document).on('click', 'a.popup-dialog', function(e) {
    e.preventDefault();
    $.contratLookup('ajax.php/' + $(this).attr('href').substr(1), function (contrat) {
        var url = window.location.href;
        if (contrat && contrat.id)
            url = 'contrat.php?id='+contrat.id;
        $.pjax({url: url, container: '#pjax-container'})
        return false;
     });

    return false;
});

</script>
