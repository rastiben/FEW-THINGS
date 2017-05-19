<div ng-controller="contratCtrl">

  <modal class="contratModals" lolo="modal1" data-header="{{header}}" data-contrat="contrat" data-valid="{{valid}}" data-ng-click-left-button="cancel()" data-ng-click-right-button="save(contrat)"></modal>
  <a href="#{{modal1}}" role="button" class="btn btn-success newContrat" ng-click="modalInfo('Ajout d\'un contrat','Créer le contrat',null,'create')" data-toggle="modal">Nouveau contrat</a>

  <div class="block">
    <table class="table table-striped contratTable">
      <thead>
        <th>Code<span class="sortorder" ng-click="sortBy('code')" ng-class="{'glyphicon glyphicon-sort-by-alphabet-alt':reverse && propertyName=='code' ,'glyphicon glyphicon-sort-by-alphabet':!reverse || propertyName != 'code'}"></span>
          <input ng-model="codeFilter" />
        </th>
        <th>Organisation<span class="sortorder" ng-click="sortBy('org')"  ng-class="{'glyphicon glyphicon-sort-by-alphabet-alt':reverse && propertyName=='org','glyphicon glyphicon-sort-by-alphabet':!reverse || propertyName != 'org'}"></span>
          <input ng-model="orgFilter" />
        </th>
        <th>Etat<span class="sortorder" ng-click="sortBy('etat')" ng-class="{'glyphicon glyphicon-sort-by-alphabet-alt':reverse && propertyName=='etat','glyphicon glyphicon-sort-by-alphabet':!reverse || propertyName != 'etat'}"></span>
          <input ng-model="etatFilter" />
        </th>
        <th>Date de debut<span class="sortorder" ng-click="sortBy('date_debut')" ng-class="{'glyphicon glyphicon-sort-by-alphabet-alt':reverse && propertyName=='date_debut','glyphicon glyphicon-sort-by-alphabet':!reverse || propertyName != 'date_debut'}"></span>
          <input ng-model="date_debutFilter" />
        </th>
        <th>Date de fin<span class="sortorder" ng-click="sortBy('date_fin')" ng-class="{'glyphicon glyphicon-sort-by-alphabet-alt':reverse && propertyName=='date_fin','glyphicon glyphicon-sort-by-alphabet':!reverse || propertyName != 'date_fin'}"></span>
          <input ng-model="date_finFilter" />
        </th>
        <th>Type<span class="sortorder" ng-click="sortBy('type')" ng-class="{'glyphicon glyphicon-sort-by-alphabet-alt':reverse && propertyName=='type','glyphicon glyphicon-sort-by-alphabet':!reverse || propertyName != 'type'}"></span>
          <input ng-model="typeFilter" />
        </th>
        <th>Prochaine date<span class="sortorder" ng-click="sortBy('created')" ng-class="{'glyphicon glyphicon-sort-by-alphabet-alt':reverse && propertyName=='created','glyphicon glyphicon-sort-by-alphabet':!reverse || propertyName != 'created'}"></span>
          <input ng-model="createdFilter" />
        </th>
        <th style="vertical-align: middle;">Supprimer</th>
      </thead>
      <tbody>
        <tr href="#{{modal1}}" ng-click="modalInfo('Modification du contrat : ','Modifier le contrat',contrat,'update')" data-toggle="modal" ng-repeat="contrat in contrats | orderBy:propertyName:reverse | filter:{code: codeFilter, org:orgFilter, etat:etatFilter, date_debut:date_debutFilter, date_fin:date_finFilter, type:typeFilter, created:createdFilter}">
          <td>{{contrat.code}}</td>
          <td>{{contrat.org}}</td>
          <td>{{contrat.etat}}</td>
          <td>{{contrat.date_debut}}</td>
          <td>{{contrat.date_fin}}</td>
          <td>{{contrat.type}}</td>
          <td>{{contrat.date_prochaine_facture}}</td>
          <td><button ng-click="remove(contrat); $event.stopPropagation();" class="btn btn-danger"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button></td>
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
