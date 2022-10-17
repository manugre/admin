<?php
/*

Genère les facturs clients

*/
if (!(defined('ROOT_DIR'))) {
    define('ROOT_DIR', '../'); // répertoire racine
}
// pdf
require(ROOT_DIR. 'clients/scripts/include/pdf/fpdf/fpdf.php');

// on intégre les fonctions de base GLPI 
include ROOT_DIR . 'clients/scripts/include/glpi_function.php';
if ((!isset($_GET['pass'])) && ($_GET['pass'] != "54321")) exit("utilisation interdite");
$mois = $_GET['mois'];  // mois du Début de la résa
$annee='2022';

$clients = array( // GLPI D => Dolibarr ID
    '264' => array('id' => '200','price'=>'2.5', 'nom'=> 'IDA' ),  // IDA
    '312' => array('id' => '223','price'=>'5', 'nom'=> 'BO'),     // BO
    '317' => array('id' => '224','price'=>'5', 'nom'=> 'HAP'),  // HAP
    '331' => array('id' => '225','price'=>'5', 'nom'=> 'MIM'),     // MIM
);


selectURL('kit');

$user = USER_ADM_TK;
$session = getSession($user);
$moisLettre = array(1=>'janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre');
$taille_colone = array(40, 35, 35, 20, 50);
$header = array('Logement', 'Début', 'Fin', 'Durée (jours)', 'Locataire');


/***************** */
class PDF extends FPDF
{
    // Tableau coloré
    function FancyTable($header, $data, $w)
    {
        // Couleurs, épaisseur du trait et police grasse
        $this->SetFillColor(255, 0, 0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(.3);
        $this->SetFont('', 'B', '8');
        // En-tête
        for ($i = 0; $i < count($header); $i++)
            $this->Cell($w[$i], 7, utf8_decode($header[$i]), 1, 0, 'C', true);
        $this->Ln();
        // Restauration des couleurs et de la police
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Données
        $fill = false;
        foreach ($data as $row) {
            $this->Cell($w[0], 6, current($row), 'LR', 0, 'L', $fill);
            $this->Cell($w[1], 6, next($row), 'LR', 0, 'L', $fill);
            $this->Cell($w[2], 6, next($row), 'LR', 0, 'L', $fill);
            $this->Cell($w[3], 6, next($row), 'LR', 0, 'L', $fill);
            $this->Cell($w[4], 6, next($row), 'LR', 0, 'L', $fill);

            $this->Ln();
            $fill = !$fill;
        }
        // Trait de terminaison
        $this->Cell(array_sum($w), 0, '', 'T');
    }
    function Footer()
    {
        //Go to 1.5 cm from bottom
        $this->SetY(-15);
        //Select Arial italic 8
        $this->SetFont('Arial','I',6);
        //Print centered page number
        $this->Cell(0,10, 'KeepInTouch  -  Page '.$this->PageNo(),0,0,'C');
    }
}

/************* */

echo "On compte les résas sur le mois " . $_GET['mois'] . "<br>\n";

//foreach ($clients as $glpiID => $crmID) {
foreach ($clients as $glpiID =>$cust) {
	$crmID=$cust['id'];
	$price=$cust['price'];
	$client=$cust['nom'];
	
    $options['entities_id'] = $glpiID;
    $options['is_recursive'] = "true";

    echo "Position sur l'entité id $glpiID :".(myAction('changeActiveEntities', $options, POST, $session))."<br>\n";
    $lgt = myAction(LOGEMENT, NULL, GET, $session);
    $lgtname = array_column($lgt, 'name', 'id');
	$nblogt=0;
	if (count($lgt)){
	foreach ($lgt as $l){
		if ($l['code_pi']!='-') $nblogt++;
	}
	}
	echo '--> nombre de logement équipés : '.$nblogt.'<br>';


	// on compte les dégradations	
	$tickets = myAction('Ticket', 'searchText[name]=' . LOGEMENT, GET, $session);
	$deg=0;
	if (count($tickets)){
	foreach ($tickets as $tk){
		if (!isset($tk['date'])) pre($tk);
		  $d = date_create_from_format('Y-m-d H:i:s', $tk['date']);
		   if ($d === false) {
                echo "Incorrect date string";
            } else {
                $opendate = $d->getTimestamp();
            }
			if ((time() - $opendate) < (30 * 24 * 3600)) { // 30 jours, approximations
				$deg++;
			}
	}
	}
	echo '--> nombre de degradation : '.$deg.'<br>';
	
    $resait = myAction('ReservationItem', 'searchText[itemtype]=' . LOGEMENT, GET, $session);
    $resa_id = array_column($resait, 'items_id', 'id');
    $i = 0;
	$data=array();
	
    $allres = myAction('reservation', NULL, GET, $session);
    foreach ($allres as $r) {
        $zz = json_decode($r['comment'], true);
        if ($zz) {
            //   if ((isset($zz['check_out_date'])) && (isset($resa_id[$r['reservationitems_id']]))) {
            if (isset($resa_id[$r['reservationitems_id']])) {
                $r_mois = date("m", strtotime($r['begin']));
                if ($r_mois == $mois) {
                    //   echo $lgtname[$resa_id[$r['reservationitems_id']]] .' : '. $r['begin'].' -> '.$r['end']."\n";
                    $data[$i]['logement'] = ucfirst(strtolower($lgtname[$resa_id[$r['reservationitems_id']]]));
                    $data[$i]['debut'] = $r['begin'];
                    $data[$i]['fin'] = $r['end'];
                    $data[$i]['duree (jours)'] = ceil((strtotime($r['end']) - strtotime($r['begin'])) / 86400);
                    $data[$i]['locataire'] = json_decode($r['comment'], true)['email'];
                    $i++;
                }
            }
        }
    }
    $columns = array_column($data, 'logement');
    array_multisort($columns, SORT_ASC, $data);
    //file_put_contents(TMP_DIR.$crmID . '-' . $mois.'.csv', array2csv($data));
	$filepdf=TMP_DIR.$client . '-' . $mois.'.pdf';

$pdf = new PDF();
// Titres des colonnes
$pdf->SetFont('Arial','',10);
$pdf->AddPage();
$pdf->SetTextColor(0);
$pdf->SetFont('');
$pdf->Write(0,utf8_decode("Détails des locations \n"));
$pdf->FancyTable($header,$data, $taille_colone);
$pdf->Footer();
$pdf->Output('F',$filepdf , true);

    echo " --> nombre de resa : $i<br> \n";
    $options = array(
        'brouillon' => '1',
        'socid' => $crmID,
        "mode_reglement_code" => "VIR",
     //   'fk_bank' => '3',
		"fk_account"=> "1",
        "cond_reglement_code" => "30D",
        'date_lim_reglement' => time() + 20 * 24 * 60 * 60,  // +20 jours
        'lines' => array(
            array(
                "desc" => "Facturation du module Etat des lieux \n \n Check-in effectués au cours du mois : ".$moisLettre[$mois]." 2022 \n \n \nSatistiques : \nNombre de logement équipés: $nblogt\nNombre de dégradations sur le mois : $deg\n",
                "product_type" => "1",
                "qty" => "$i",
                "subprice" => $price,
                "fk_product" => "1",
                "fk_product_type" => "1",
                "code_ventilation" => "0",
                "fk_accounting_account" => "0",
				"tva_tx"=> "20.0000",
            )
        ),
    );
	$apigo=CallAPI('POST', 'invoices', json_encode($options));
   	echo " ---> ".print_r($apigo, true)."<br>\n";

    if (is_numeric($apigo)) {
		$id=$apigo;
        $options = array(
            "filename" => $client . '-' . $moisLettre[$mois].'.pdf',
            "modulepart" => "invoice",
            "ref" => "(PROV$id)",
            "subdir" => "",
            "filecontent" => base64_encode(file_get_contents($filepdf)),
            "fileencoding" => "base64",
            "overwriteifexists" => "0",
        );
        $id = json_decode(CallAPI('POST', 'documents/upload', json_encode($options)));
		 echo ' --->Fihcier ajoutée : ' .print_r($id, true) ."<br>\n";
         
		unlink($filepdf);

  
    } else {
        echo 'Erreur à la création :' . print_r($id, true) . "<br><br><hr>\n";
    }
	exit();
}

exit();


function array2csv(array &$array)
{
    if (count($array) == 0) {
        return null;
    }
    ob_start();
    $df = fopen("php://output", 'w');
    fputcsv($df, array_keys(reset($array)), ';');
    foreach ($array as $row) {
        fputcsv($df, $row, ';');
    }
    fclose($df);
    return ob_get_clean();
}

function CallAPI($method, $options, $data = false)
{
    $url = 'https://crm.keepintouch.immo/api/index.php/' . $options;
    $apikey = 'd0gI3BALsp0lk7x0H0G4T9rJdnEQ1Pb0';
    $curl = curl_init();
    $httpheader = ['DOLAPIKEY: ' . $apikey];
 //   echo "URL API : " . $url . "\n";

    switch ($method) {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);
            $httpheader[] = "Content-Type:application/json";

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

            break;
        case "PUT":

            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
            $httpheader[] = "Content-Type:application/json";

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    // Optional Authentication:
    //    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    //    curl_setopt($curl, CURLOPT_USERPWD, "username:password");

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $httpheader);

    $result = curl_exec($curl);

    curl_close($curl);

    return $result;
}

