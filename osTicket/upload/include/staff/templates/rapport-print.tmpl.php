<html>

<head>
   <?php include_once(SCP_DIR . '/Request/Rapport.php') ?>
    <style type="text/css">
        @page {
            header: html_def;
            footer: html_def;
            margin: 10mm;
            margin-top: 5mm;
            margin-bottom: 22mm;
        }

        .round{
            border:0.1mm solid #220044;
            background-color: #f0f2ff;
            background-gradient: linear #c7cdde #f0f2ff 0 1 0 0.5;
            border-top-left-radius: 4em;
            background-clip: border-box;
        }

<?php include ROOT_DIR . 'css/thread.css';?>

    </style>
</head>
<body>

<?php
    $rapport = Rapport::getInstance()->getRapports($_GET['id']);
    $horaires = Rapport::getInstance()->getRapportsHoraires($_GET['id']);
?>

<!--<htmlpageheader name="def" style="display:none">
<!--<?php if ($logo = $cfg->getClientLogo()) { ?>
    <img src="cid:<?php echo $logo->getKey(); ?>" class="logo"/>
<?php } else { ?>
    <img src="<?php echo INCLUDE_DIR . 'fpdf/print-logo.png'; ?>" class="logo"/>
<?php } ?>
    <div class="hr">&nbsp;</div>
    <table><tr>
        <td class="flush-left"><?php echo (string) $ost->company; ?></td>
        <td class="flush-right"><?php echo Format::daydatetime(Misc::gmtime()); ?></td>
    </tr></table>
</htmlpageheader>-->

<htmlpagefooter name="def" style="display:none">
    <!--<div class="hr">&nbsp;</div>
    <table width="100%"><tr><td class="flush-left">
        Ticket #<?php echo $ticket->getNumber(); ?> printed by
        <?php echo $thisstaff->getUserName(); ?> on
        <?php echo Format::daydatetime(Misc::gmtime()); ?>
    </td>
    <td class="flush-right">
        Page {PAGENO}
    </td>
    </tr></table>-->
</htmlpagefooter>

<!-- Ticket metadata -->
<!--<h1>Ticket #<?php echo $ticket->getNumber(); ?></h1>-->
<!--<span><?php print_r($rapport)?></span>-->
<table style="border-collapse: collapse;border-spacing: 0;border:1px solid black;margin-bottom:15px" width="100%">
  <tbody>
    <tr>
		<td colspan="1"><img height="100" src="<?php echo INCLUDE_DIR . 'fpdf/logo_OK.jpg'; ?>" class="logo"/></td>
		<td colspan="2" style="border-right:1px solid black;">VIENNE DOCUMENTIQUE S.A.S<br>
		150 avenue des Hauts de la Chaume<br>
		86280 SAINT BENOIT<br>
		Téléphone : 05.49.30.31.31<br>
		Télécopie : 05.49.53.69.53<br>
		Email:gclemenceau@viennedoc.com<br>
		Site internet: http://www.viennedoc.com</td>
		<td colspan="3"><span>
           ADSEA _<br>
           ADSEA <br>
           MR LAFON <br>
           1 RUE DU SENTIER <br>
           - <br>
           86180 BUXEROLLES<br>
           France
       </span></td>
    </tr>
    <tr style="text-align:center">
       <th colspan="6" style="text-align:center;border-top:1px solid black;border-bottom:1px solid black" height="30">
            RAPPORT D'INTERVENTION N° <?php echo $rapport[0]['id'] ?>
        </th>
    </tr>
    <tr style="text-align:center">
       <th style="text-align:center;border-bottom:1px solid black;border-left:1px solid black;border-right:1px solid black;" height="20">
            N° DU TICKET
        </th>
        <th style="text-align:center;border-bottom:1px solid black;border-left:1px solid black;border-right:1px solid black;">
            Date d'appel
        </th>
        <th style="text-align:center;border-bottom:1px solid black;border-left:1px solid black;border-right:1px solid black;">
            Heure
        </th>
        <th style="text-align:center;border-bottom:1px solid black;border-left:1px solid black;border-right:1px solid black;">
            Intervenant
        </th>
        <th style="text-align:center;border-bottom:1px solid black;border-left:1px solid black;border-right:1px solid black;">
            N° de contrat
        </th>
        <th style="text-align:center;border-bottom:1px solid black;border-left:1px solid black;border-right:1px solid black;">
            Code site
        </th>
    </tr>
    <tr>
        <td style="text-align:center;border-left:1px solid black;border-right:1px solid black;">
        <?php echo $ticket->getId(); ?>
        </td>
        <td style="text-align:center;border-left:1px solid black;border-right:1px solid black;">
                   <?php $createDate = new DateTime($ticket->getCreateDate());
                    echo $createDate->format('d/m/Y'); ?>
                    </td>
        <td style="text-align:center;border-left:1px solid black;border-right:1px solid black;">
        <?php echo $createDate->format('H:i'); ?>
        </td>
        <td style="text-align:center;border-left:1px solid black;border-right:1px solid black;">
        <?php echo $rapport[0]['lastname']; ?>
        </td>
        <td style="text-align:center;border-left:1px solid black;border-right:1px solid black;">

        </td>
        <td style="text-align:center;border-left:1px solid black;border-right:1px solid black;">
        <?php echo $ticket->getOwner()->getOrganization()->getName(); ?>
        </td>
    </tr>
    <tr>
        <th colspan="1" style="text-align:center;border-top:1px solid black;border-right:1px solid black;" >Demande de</th>
        <th colspan="5" style="text-align:center;border-top:1px solid black;border-left:1px solid black;">Sujet</th>
    </tr>
    <tr>
        <td colspan="1" style="text-align:center;border-top:1px solid black;border-right:1px solid black;"><?php echo $rapport[0]['firstname'] . ' ' . $rapport[0]['lastname'] ?>
        <td colspan="5" style="text-align:center;border-top:1px solid black;border-left:1px solid black;"><?php echo $ticket->getSubject(); ?></td></td>
    </tr>
    </tbody>
</table>
        <?php
        foreach($horaires as $horaire){
            ?>
<table style="border-collapse: collapse;border-spacing: 0;border-right:1px solid black;margin-top:15px" width="100%">
    <tbody>
    <tr>
        <th></th>
        <th style="border:1px solid black;" >Libellé article et commentaires</th>
        <th style="border:1px solid black;">quantité</th>
        <th style="border:1px solid black;">P.U.</th>
        <th style="border-bottom:1px solid black;border-left:1px solid black;border-top:1px solid black">Prix total</th>
    </tr>
    <tr>
        <td><div class="round">BLABLA</div></td>
       <td style="border:1px solid black;">

            <span>Note d'intervention du <?php $arriveInter = new DateTime($horaire['arrive_inter']);
                    echo $arriveInter->format('d/m/Y'); ?></span><br><br>
            <span>Commentaires : </span><br><br>
            <?php echo $horaire['comment'] ?>
            <br><br>
       </td>
        <td style="border:1px solid black;"></td>
        <td style="border:1px solid black;"></td>
        <td style="border:1px solid black;"></td>
       </tr>
    </tbody>
</table>
       <?php } ?>
</body>
</html>
