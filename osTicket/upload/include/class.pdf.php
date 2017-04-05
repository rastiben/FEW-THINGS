<?php
/*********************************************************************
    class.pdf.php

    Ticket PDF Export

    Peter Rotich <peter@osticket.com>
    Copyright (c)  2006-2013 osTicket
    http://www.osticket.com

    Released under the GNU General Public License WITHOUT ANY WARRANTY.
    See LICENSE.TXT for details.

    vim: expandtab sw=4 ts=4 sts=4:
**********************************************************************/

define('THIS_DIR', str_replace('\\', '/', Misc::realpath(dirname(__FILE__))) . '/'); //Include path..

require_once(INCLUDE_DIR.'mpdf/mpdf.php');

class mPDFWithLocalImages extends mPDF {
    function WriteHtml($html) {
        static $filenumber = 1;
        $args = func_get_args();
        $self = $this;
        $images = $cids = array();
        // Try and get information for all the files in one query
        if (preg_match_all('/"cid:([\w._-]{32})"/', $html, $cids)) {
            foreach (AttachmentFile::objects()
                ->filter(array('key__in' => $cids[1]))
                as $file
            ) {
                $images[strtolower($file->getKey())] = $file;
            }
        }
        $args[0] = preg_replace_callback('/"cid:([\w.-]{32})"/',
            function($match) use ($self, $images, &$filenumber) {
                if (!($file = @$images[strtolower($match[1])]))
                    return $match[0];
                $key = "__attached_file_".$filenumber++;
                $self->{$key} = $file->getData();
                return 'var:'.$key;
            },
            $html
        );
        return call_user_func_array(array('parent', 'WriteHtml'), $args);
    }
}

class Ticket2PDF extends mPDFWithLocalImages
{

	var $includenotes = false;

	var $pageOffset = 0;

    var $ticket = null;

	function __construct($ticket, $psize='Letter', $notes=false,$rapport) {
        global $thisstaff;

        $this->ticket = $ticket;
        $this->includenotes = $notes;

        parent::__construct('', $psize);

        if($rapport)
            $this->print_rapport();
        else
            $this->_print();
	}

    function getTicket() {
        return $this->ticket;
    }

    function _print() {
        global $thisstaff, $thisclient, $cfg;

        if(!($ticket=$this->getTicket()))
            return;

        ob_start();
        if ($thisstaff)
            include STAFFINC_DIR.'templates/ticket-print.tmpl.php';
        elseif ($thisclient)
            include CLIENTINC_DIR.'templates/ticket-print.tmpl.php';
        else
            return;
        $html = ob_get_clean();

        $this->WriteHtml($html, 0, true, true);
    }

    function print_rapport(){
        global $thisstaff, $thisclient, $cfg;

        if(!($ticket=$this->getTicket()))
            return;

        ob_start();
            include STAFFINC_DIR.'templates/rapport-print.tmpl.php';
        $html = ob_get_clean();


        $this->WriteHtml($html, 0, true, true);

        $horaires = Rapport::getInstance()->getRapportsHoraires($_GET['idR']);
        foreach($horaires as $horaire){

$arriveInter = new DateTime($horaire['arrive_inter']);
$departInter = new DateTime($horaire['depart_inter']);

    $html = '<table autosize="1" style="page-break-inside: auto;border-collapse: collapse;border-spacing: 0;border-right:1px solid black;margin-top:15px" width="100%">
       <thead>
        <tr>
            <th width="70%" style="border:1px solid black;" >Libellé article et commentaires</th>
            <th width="10%" style="border:1px solid black;">quantité</th>
            <th width="10%" style="border:1px solid black;">P.U.</th>
            <th width="10%" style="border-bottom:1px solid black;border-left:1px solid black;border-top:1px solid black">Prix total</th>
        </tr>
       </thead>
        <tbody>
        <tr>
       <td style="border:1px solid black;border-bottom:none">

            <span>Note d\'intervention du'. $arriveInter->format('d/m/Y') .'</span><br>
            <span>De : '. $arriveInter->format('H:i') . ' à ' . $departInter->format('H:i') .'</span>
            <br><br>
            <span>Commentaires : </span><br><br>
                '.$horaire['comment'].'
            <br><br>
       </td>
        <td style="border:1px solid black;border-bottom:none"></td>
        <td style="border:1px solid black;border-bottom:none"></td>
        <td style="border:1px solid black;border-bottom:none"></td>
       </tr></tbody></table>';

        $this->WriteHtml($html, 0, true, true);

        $percent = ($this->y * 100) / $this->h;
        $manquant = 100-$percent;

        //$height = ($this->h - $this->y) * (($manquant*4)/100);
        $height = ($this->h - $this->y) + (((100-$percent)*$this->y)/$percent);
        //position:absolute;bottom:220px;width:1000px
        $html = "<div style='position:absolute;left:37.8px;right:37.8px;border:1px solid black;border-top:none'>
        TOTO
        </div>";
        /*$html = '<table autosize="1" style="page-break-inside: auto;border-collapse: collapse;border-spacing: 0;border-right:1px solid black" width="100%">
        <tbody>
        <tr>
            <td style="border:1px solid black;border-top:none;width:70%;padding:'.$height.'px">
            '.$height.' '.(((100-$percent)*$this->y)/$percent).'</td>
            <td style="border:1px solid black;border-top:none;width:10%"></td>
            <td style="border:1px solid black;border-top:none;width:10%"></td>
            <td style="border:1px solid black;border-top:none;width:10%"></td>
       </tr></tbody></table>';*/

        $this->WriteHtml($html, 0, true, true);


        //$this->WriteHtml("<p>".$this->h." ".$this->y." ".$height."</p>", 0, true, true);
    }

        $data = (array)json_decode(trim(file_get_contents('php://input')));
        $img = $data['img'];
        $html = '<div class="signature"><h5>Cachet et signature du client le</h5>';
        if(!empty($img)){
            $html .= '<img src="'. $img . '"></img>';
        }
        $html .= '</div>';
        $this->SetHTMLFooter($html);
    }
}


// Task print
class Task2PDF extends mPDFWithLocalImages {

    var $options = array();
    var $task = null;

    function __construct($task, $options=array()) {

        $this->task = $task;
        $this->options = $options;

        parent::__construct('', $this->options['psize']);
        $this->_print();
    }

    function _print() {
        global $thisstaff, $cfg;

        if (!($task=$this->task) || !$thisstaff)
            return;

        ob_start();
        include STAFFINC_DIR.'templates/task-print.tmpl.php';
        $html = ob_get_clean();
        $this->WriteHtml($html, 0, true, true);

    }
}

?>
