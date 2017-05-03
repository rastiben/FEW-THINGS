
<div class="block rapportListe" style="text-align:center">
       <div class="loading blank">
               <div class="info">
                   <h3>Récupération des informations</h3>
                   <div class="sk-circle">
                    <div class="windows8">
                        <div class="wBall" id="wBall_1">
                            <div class="wInnerBall"></div>
                        </div>
                        <div class="wBall" id="wBall_2">
                            <div class="wInnerBall"></div>
                        </div>
                        <div class="wBall" id="wBall_3">
                            <div class="wInnerBall"></div>
                        </div>
                        <div class="wBall" id="wBall_4">
                            <div class="wInnerBall"></div>
                        </div>
                        <div class="wBall" id="wBall_5">
                            <div class="wInnerBall"></div>
                        </div>
                    </div>
               </div>
           </div>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><strong>Ticket</strong></th>
                    <th><strong>Date Création</strong></th>
                    <th><strong>Client</strong><span data-filter="org" class="glyphicon glyphicon-filter" aria-hidden="true"></span></th>
                    <th><strong>Auteur</strong><span data-filter="auteur" class="glyphicon glyphicon-filter" aria-hidden="true"></span></th>
                    <th><strong>Type</strong><span data-filter="type" class="glyphicon glyphicon-filter" aria-hidden="true"></span></th>
                </tr>
            </thead>
            <tbdody>
                <?php foreach($rapportl as $rapport) { ?>

                    <tr>
                        <td><?= $rapport['id_ticket']; ?></td>
                        <td><?= $rapport['date_rapport']; ?></td>
                        <td><?= $rapport['ticket__user__org_name']; ?></td>
                        <td><?= $rapport['staff__firstname'] . ' ' . $rapport['staff__lastname'] ; ?></td>
                        <td><?= $rapport['topic__topic']; ?></td>
                    </tr>

                <?php } ?>
            </tbdody>
        </table>
        <?php
        if ($count) {
            echo sprintf('<ul class="pagination">%s</ul>',
                    $pageNav->getBSPageLinks(false,false));
        }
        ?>
</div>
