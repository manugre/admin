<?php
/********************
Archives d'un client vers //

********************/
if (!(defined('ROOT_DIR'))) {
	define('ROOT_DIR', $_SERVER['DOCUMENT_ROOT']); // répertoire racine
	//define('ROOT_DIR', '../../'); // répertoire racine
}

define('DEBUG', true);
define('LOG_FILE', 'archive.log');
$log=fopen(LOG_FILE, "a");

// on intègre les fonctions de base GLPI 
include ROOT_DIR . 'clients/scripts/include/glpi_function.php';

define('ARCHIVE_DIR', '/var/www/restricted/ssh/piloteimmo/glpi/archives/');


if (!isset($_GET['user'])) exit ('utilisation non valide');
/****************************
 * 
 * A modifier ici
 * 
 */
// configuration
selectURL('kit'); // kit ou clients

$sess_token=getSession($_GET['user']);
if (!$sess_token) { 
    exit ('Utilisateur invalide : '.print_r($sess_token, true));
}
$entity=myAction('Entity', NULL, GET, $sess_token);
$client=$entity[0]['name'];
mkdir(ARCHIVE_DIR.'/'.$client);

$logements=myAction(LOGEMENT, NULL, GET, $sess_token);

// Ticket
echo '<hr>Ticket<br>';
$docs_item=myAction('Document_item', NULL, GET, $sess_token);

foreach($logements as $lgt){
    if (!is_dir(ARCHIVE_DIR.'/'.$client.'/'.$lgt['name'])) mkdir(ARCHIVE_DIR.'/'.$client.'/'.$lgt['name']);
    if (!is_dir(ARCHIVE_DIR.'/'.$client.'/'.$lgt['name'].'/Degradations')) mkdir(ARCHIVE_DIR.'/'.$client.'/'.$lgt['name'].'/Degradations');
    echo 'Logement : '.$lgt['name'] ."<br>";

    $get_all_ticket = myAction(LOGEMENT. '/' . $lgt['id'] . '/Ticket/', 'order=DESC', GET, $sess_token);

	foreach ($get_all_ticket as $ticket) {
		$info = json_decode($ticket['name'], true);
		if ($ticket['itilcategories_id'] == INCIDENT) {
            echo ' --> ticket : '.$ticket['id'] ."<br>";

            foreach($docs_item as $item) {
                if ($item['items_id']==$ticket['id']){
                    $photo=getMyDoc($item['documents_id'], $sess_token);
                    echo ' ---> photo : '.$item['documents_id']."<br>";
                    if (!is_dir(ARCHIVE_DIR.'/'.$client.'/'.$lgt['name'].'/Degradations/'. $ticket['id'])) mkdir(ARCHIVE_DIR.'/'.$client.'/'.$lgt['name'].'/Degradations/'. $ticket['id']);
                    file_put_contents(ARCHIVE_DIR.'/'.$client.'/'.$lgt['name'].'/Degradations/'. $ticket['id'].'/'.$item['date_creation'].'.jpg', $photo);
                }
            }
        }
    }
}

// EDL
echo '<hr>EDL<br>';
$docs_item=myAction('Document_item', NULL, GET, $sess_token);

$get_all_resa = myAction('/Reservation/', 'order=DESC', GET, $sess_token);
$resa_item=myAction('ReservationItem', NULL, GET, $sess_token);
$resa_itemID=array_column($resa_item, 'id', 'items_id');

foreach($logements as $lgt){
    if (!is_dir(ARCHIVE_DIR.'/'.$client.'/'.$lgt['name'])) mkdir(ARCHIVE_DIR.'/'.$client.'/'.$lgt['name']);
    if (!is_dir(ARCHIVE_DIR.'/'.$client.'/'.$lgt['name'].'/EDL')) mkdir(ARCHIVE_DIR.'/'.$client.'/'.$lgt['name'].'/EDL');
    echo 'Logement : '.$lgt['name'] ."<br>";


	foreach ($get_all_resa as $resa) {
        echo ' --> EDL : '.$resa['id'] ."<br>";
        //if ($resa['reservationitems_id']==$resa_itemID[$lgt['id']]){
            $edl=json_decode($resa['comment'], true);
            if (isset($edl['in_doc_id'])){
                echo ' ---> EDL IN : '.$edl['in_doc_id']."<br>";
                $photo=getMyDoc($edl['in_doc_id'], $sess_token);
                file_put_contents(ARCHIVE_DIR.'/'.$client.'/'.$lgt['name'].'/EDL/IN'.$edl['date_arrivee'] .'.jpg', $photo);
            } 
    }        
}
?>
