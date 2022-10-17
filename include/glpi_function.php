<?php
//$user_token="Uct30z4u4Olbkk2XTZLcQ9RGC3Uq5XPR4Sgsegjc"; // user : pm_un

if (!(defined('ROOT_DIR'))) {
	define('ROOT_DIR', $_SERVER['DOCUMENT_ROOT']); // répertoire racine
}

//emon
//include('api_emon.php');

define('PI_ADM_TK', 'XxmMrxzK1RjSk0d2acTOc6ATqnM96ChDdNMDS0J7'); // user : piadm
define('USER_ADM_TK', 'PbEr5DN5AxujklWg6j2YzqlYgCtZCcV84dAaLoOk'); // super_admin
define('APP_TOKEN', 'JrPPyJXURC0j2gpHODxl94QYUxaouJ3LMtIhI2pS');

if (!(defined('DOC_ROOT'))) {
	$root = '/';
	$zz = explode('/', $_SERVER['DOCUMENT_ROOT']);
	for ($i = 1; $i < (count($zz) - 3); $i++) {
		$root .= $zz[$i].'/';
	}
	//$root .= '/';
	define('DOC_ROOT', $root); // /var/www/restricted/ssh/piloteimmo/ 
}
//define('TMP_UPLOAD_PATH', ROOT_DIR . 'glpi/files/_uploads/' );
define('GLPI_FILE_PATH', ROOT_DIR . 'glpi/files/');
define('JS_UPLOAD_PATH', ROOT_DIR . 'clients/scripts/upload/_upload/');
define('TMP_UPLOAD_PATH', JS_UPLOAD_PATH);
define('TMP_DIR', DOC_ROOT . '/glpi/tmp/');

define('PORTAIL', 'https://portail.pilote.immo');

define('GET', FALSE);
define('POST', 1);
define('PUT', 2);
define('DELETE', 3);

define('CODE_PI_EQPT', 3); //Code PI Associé item : PluginGenericobjectEqpt / Code_eqpt
define('TICKET_GPA_NUMBER', 76666); //76666 <= Ticket /  PiloteImmo/GPA_number
define('EQPT_ID', '2');
define('EQPT_ENTITY_NAME', '80');

define('CODE_PI_LOC', '12');
define('CODE_LOC_ID', '2');

define('LOC_GPA', 'Commissionnement'); // inutilisé depuis 30/07/2020
define('INCIDENT', '1'); // id de la catégogie de ticket incident = exploitation
define('TICK_PUBLIC', '8'); // id de la catégogie de ticket incident
define('GPA', '2');
define('INTERVENTION', '5');
define('MAINTENANCE', '8'); // Maintenance préventive

define('TK_PUBLIC', '1'); // Ticket de type public
define('TK_PRIVE', '2'); // Ticket de type priv

define('DOCUMENT_IV', '14'); // catégogie info visiteur
define('DOCUMENT_CORPO', '2'); // Photo de l'asset / du logement ...
define('DOCUMENT_TECH', '5'); // Photo du ticket
define('DOCUMENT_EDL', '18'); // Photo des EDL sortant 
define('DOCUMENT_NOTICE', '13'); // 
define('DOCUMENT_PHOTO', '19'); // 
define('DOCUMENT_EDL_PDF', '20'); // PDF  des EDL sortant 
define('DOCUMENT_LOGO', '1');


define('RANGE_GLPI', '0-10000');

define("IMAGE_TAILLE", "4200000");


// logement details
define('LOGEMENT', 'PluginGenericobjectLogement');
define('DETAIL', 'PluginGenericobjectDetail');

// visiteur
define('IV_USER', 'PluginGenericobjectUser');

define('LGT_ID_FIELD', '124'); // 125
define('COMMENT_FIELD', '16');
define('REF_ID_FIELD', '123');
define('OK_FIELD', '122'); //124
define('ETAT_FIELD', '125'); //Etat du détail du logement

define('LGT_IN', '3');
define('LGT_OUT', '4');
define('LGT_R2R', '2');



// log error
error_reporting(E_ALL); // Error engine - always E_ALL!
ini_set('ignore_repeated_errors', TRUE); // always TRUE
ini_set('display_errors', FALSE); // Error display - FALSE only in production environment or real server. TRUE in development environment
ini_set('log_errors', TRUE); // Error logging engine
ini_set('error_log', 'php-errors.log'); // Logging file path
ini_set('log_errors_max_len', 1024); // Logging file size


function selectURL($base = FALSE)
{
	if (!(defined('API_URL'))) {
		if ($base) {
			$dir = $base;
		} else {
			$dir = explode('/', $_SERVER['REQUEST_URI'])[1];
		}
		if ($dir == 'kit') {
			logg("--KIT--\n");
			// --> à modifier : 
			define('API_URL', 'https://pitrack-kit.pilote.immo/pitrack/apirest.php');
			define('UPLOAD_API_URL', 'https://pitrack-kit.pilote.immo/upload.php');

			//	define('API_URL', 'https://pitrack.pilote.immo/pitrack/apirest.php');
			//	define('UPLOAD_API_URL', 'https://pitrack.pilote.immo/upload.php');

			define('APP_URL', 'https://kit.pilote.immo');

			//define('PORTAIL_URL', 'https://portail.pilote.immo/kit/main/');
			define('PORTAIL_URL', '/kit/main/');
			define('BASE_PORTAIL', 'https://portail.pilote.immo/');


			$_SESSION['bdd'] = 'kit';
			define('BDD', 'kit');
			define('FROM_MAIL', 'noreply@keepintouch.immo');
		} else if ($dir == 'clients') {
			logg("--CLIENTS--\n");

			define('API_URL', 'https://pitrack.pilote.immo/pitrack/apirest.php');
			define('UPLOAD_API_URL', 'https://pitrack.pilote.immo/upload.php');
			define('APP_URL', 'https://clients.pilote.immo');

			//define('PORTAIL_URL', 'https://portail.pilote.immo/clients/main/');
			define('PORTAIL_URL', '/clients/main/');
			define('BASE_PORTAIL', 'https://portail.pilote.immo/');
			$_SESSION['bdd'] = 'clients';
			define('BDD', 'clients');
			define('FROM_MAIL', 'noreply@pilote.immo');
		} else if ($dir == 'dev') {
			logg("--DEV--\n");

			define('API_URL', 'https://pitrack-dev.pilote.immo/pitrack/apirest.php');
			define('UPLOAD_API_URL', 'https://pitrack-dev.pilote.immo/upload.php');
			define('APP_URL', 'https://clients.pilote.immo');

			//define('PORTAIL_URL', 'https://portail.pilote.immo/clients/main/');
			define('PORTAIL_URL', '/clients/main/');
			define('BASE_PORTAIL', 'https://portail.pilote.immo/');
			$_SESSION['bdd'] = 'dev';
			define('BDD', 'dev');
		} else {
			define('API_URL', 'https://pitrack.pilote.immo/pitrack/apirest.php');
			define('UPLOAD_API_URL', 'https://pitrack.pilote.immo/upload.php');
			define('APP_URL', 'https://clients.pilote.immo');
			//define('PORTAIL_URL', 'https://portail.pilote.immo/clients/main/');
			define('PORTAIL_URL', '/clients/main/');
			define('BASE_PORTAIL', 'https://portail.pilote.immo/');
			$_SESSION['bdd'] = 'clients';
			define('BDD', 'clients');


			logg('Warrinig : SelectURL-> Choix par defaut. Risque Erreur ' . "\n");
		}
	}
}

// fonction inutilié depuis 29/04/2022
/*
function selectAPP($sess_token)
{
	if (!(defined('APP_URL'))) {
		$fs=myAction('getFullSession', NULL, GET, $sess_token);
		if (isset($fs['session']['glpiparententities']['209'])){
			//209= id de entité /KIT
			define('APP_URL', 'https://kit.pilote.immo');
		}
		else {
			define('APP_URL', 'https://clients.pilote.immo');
		}
	}
	// logg("API_URL : ".API_URL ."\n");   
}
*/

function logg($str, $d = true)
{
	global $log;

	if (!(defined('DEBUG'))) {
		define('DEBUG', FALSE);
	}
	if (DEBUG) {
		if ($d) {
			fwrite($log, date("Y-m-d H:i:s") . '- ' . $str);
		} else {
			fwrite($log, $str);
		}
	}
}


// fonction non utilisée
/*
function setPortailUrl($sess_token, $entity)
{
	if (!defined('PORTAIL_URL')) {
		$get = myAction('entity/' . $entity, NULL, GET, $sess_token);
		if (isset($get['completename']))  {
			$completename = explode('>', $get['completename']);
		}
		else {
			$completename ='';
		}
//		define('PORTAIL_URL', 'https://portail.pilote.immo/clients/' . trim($completename[1]) . '/');

		return $completename;
	} else return FALSE;
}
*/


function getSession($user_token, $entity = FALSE)
{
	if ((!(defined('API_URL'))) || ($entity)) {
		//	logg(" getSession -> selected url by entity : $entity \n");
		selectURL($entity);
	}
	$ch = curl_init();
	$url = API_URL . '/initSession?Content-Type=%20application/json&app_token=' . APP_TOKEN . '&user_token=' . $user_token;
	//logg ( "getsession url : ".$url."\n");

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$json = curl_exec($ch);
	curl_close($ch);

	// returned json string will look like this: {"code":1,"data":"OK"}
	$obj = json_decode($json, true);
	//print_r($obj);
	if (!isset($obj['session_token'])) {
		logg('getSession Error : !isset($obj[session_token])' . "\n");
		logg('ch:' . var_export($ch, false) . "\n");
		logg('obj:' . print_r($obj, true) . "\n");
		return 0;
	}
	if (strlen($obj['session_token']) < 10) {
		logg('getSession Problem: ' . print_r($obj, true));
	}
	//selectAPP($obj['session_token']);
	return $obj['session_token'];
}


function killSession()
{
	global $sess_token;
	$headers = array(
		('Content-Type: application/json'),
		('App-Token: ' . APP_TOKEN),
		('Session-Token: ' . $sess_token)
	);

	$ch = curl_init();
	$url = API_URL . "/killSession";

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$json = curl_exec($ch);
	curl_close($ch);
}
function killmySession($sess_token)
{
	if (!(defined('API_URL'))) {
		selectURL();
	}
	$headers = array(
		('Content-Type: application/json'),
		('App-Token: ' . APP_TOKEN),
		('Session-Token: ' . $sess_token)
	);

	$ch = curl_init();
	$url = API_URL . "/killSession";
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$json = curl_exec($ch);
	curl_close($ch);

	return $json;
}

// fonction de Publication sur GLPI
// $item : Ticket, Document, ...
// $options : la variable tableau des paramétres, 
// $methode : 	POST (1) si $options est une variable tableau à passer en json en $POST,
//			 	GET (FALSE) on passe $option tel quel en ligne de commande en $GET
//				PUT (2) : mise à jour, meme format que POST
//				DELETE (3) : suppression
function myAction($item, $options, $methode, $sess_token)
{
	if (!(defined('API_URL'))) {
		selectURL();
	}
	$headers = array(
		('Content-Type: application/json'),
		('App-Token: ' . APP_TOKEN),
		('Session-Token: ' . $sess_token)
	);

	$ch = curl_init();
	if ($methode == POST) {
		$url = API_URL . '/' . $item . '/';
		if (isset($options)) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($options));
		}
		curl_setopt($ch, CURLOPT_POST, TRUE);
	} else if ($methode == PUT) {
		$url = API_URL . '/' . $item . '/';
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($options));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
	} else if ($methode == DELETE) {
		$url = API_URL . '/' . $item . '/';
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
	} else {
		$url = API_URL . '/' . $item . '?' . $options . '&range=' . RANGE_GLPI;
	}
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	//curl_setopt($ch, CURLOPT_VERBOSE, true);

	$info = curl_getinfo($ch);
	//	logg("MyAction : curl_getinfo : ".print_r($info, true) . "\n");
	$curl_error = curl_error($ch);
	//	logg("MyAction : curl_error : ".print_r($curl_error, true) . "\n");

	$request_result = curl_exec($ch);
	curl_close($ch);

	return json_decode($request_result, true);
}

function getMyDoc($ID, $sess_token)
{
	if (!(defined('API_URL'))) {
		selectURL();
	}

	//$ID : document ID
	// return : raw document

	$headers = array(
		('Accept: application/octet-stream'),
		('Content-Type: application/json'),
		('App-Token: ' . APP_TOKEN),
		('Session-Token: ' . $sess_token),
	);

	$ch = curl_init();
	$url = API_URL . '/Document/' . $ID;

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	//$info = curl_getinfo($ch);
	//	logg("MyAction : curl_getinfo : ".print_r($info, true) . "\n");
	//$curl_error = curl_error($ch);
	//	logg("MyAction : curl_error : ".print_r($curl_error, true) . "\n");

	$request_result = curl_exec($ch);
	curl_close($ch);

	return $request_result;
}

function status($indice)
{
	if ($indice == 1) {
		return 'Nouveau';
	};
	if ($indice == 2) {
		return 'En Cours';
	};
	if ($indice == 3) {
		return 'Attribué';
	};
	if ($indice == 4) {
		return 'Planifié';
	};
	if ($indice == 5) {
		return 'Résolu';
	};
	if ($indice == 6) {
		return 'Clos';
	};
}

function addAjaxUpload($nbfile, $filesname, $itemtype, $itemID, $sess_token, $docCatId = DOCUMENT_TECH, $docCatNext = FALSE, $affiche = true)
{
	if (!(defined('API_URL'))) {
		selectURL();
	}
	$out = "";
	//	logg("AddAjaxUplod : sess_token : $sess_token  docCatId : $docCatId  nbfile: $nbfile itemtype: $itemtype itemID:$itemID \n");
	$doc_id = array();
	// usage : addAjaxUpload($nbfile, $filesname, 'Ticket', $Ticket['id'], $sess_token) ;

	$first = true;

	for ($i = 1; $i <= $nbfile; $i++) {
		logg('UL Doc: fichier :' . JS_UPLOAD_PATH . $filesname[$i] . "\n");
		// on patiente un peu le temps que les fichiers arrivent...
		for ($delais = 0; $delais <= 40; $delais++) {
			if (file_exists(JS_UPLOAD_PATH . $filesname[$i])) {
				break;
			}
			$out .= '.';
			logg('.', false);

			sleep(1);
		}
		if (!file_exists(JS_UPLOAD_PATH . $filesname[$i])) {
			logg('UL Doc: le délais est coulé, il y a un pb :' . "\n");

			// si le délais est coulé, c'est qu'il y a un pb
			$out .= '<p><b> Problème avec le fichier ' .  $filesname[$i] . '</b>';
			$out .= 'Impossible de le charger<br> Problème de connection internet probable </p>';
		} else {
			//logg("exif_read_data :".print_r(exif_read_data (TMP_UPLOAD_PATH.$filesname[$i]), true));
			//on deplace le fichier au bon endroit
			/*
		    if (!rename(JS_UPLOAD_PATH . $filesname[$i], TMP_UPLOAD_PATH . $filesname[$i] )){
		        logg(' rename error: JS_UPLOAD_PATH . $filesname[$i] : '.file_exists(JS_UPLOAD_PATH . $filesname[$i]));
		        logg(' rename error: TMP_UPLOAD_PATH . $filesname[$i]  : '.file_exists(TMP_UPLOAD_PATH . $filesname[$i] ));
		    }
		*/
			// on récupère les infos de l'image			

			try {
				$img_info = @getimagesize(TMP_UPLOAD_PATH . $filesname[$i]);
				if ($img_info) {
					$type = 'image';
					$filesnameOK = $filesname[$i];
				} else {
					$type = 'none';
					$img_info[0] = 0;
					$img_info[1] = 0;
				}
			} catch (Exception $e) {
				if (mime_content_type(TMP_UPLOAD_PATH . $filesname[$i]) == 'application/pdf')
					$type = 'pdf';
				$z = explode('---', $filesname[$i]);
				isset($z[1]) ? $filesnameOK = $z[1] : $filesnameOK = $filesname[$i];

				$img_info[0] = 0;
				$img_info[1] = 0;
			}
			// on intercepte si c'est une image, et on la remet droite
			if ($img_info[0] > 0) {
				$result = correctImageOrientation(TMP_UPLOAD_PATH . $filesname[$i]);
				//echo 'Image remisee droite<br>';
				// si problème
				if (!($result)) {
					$out .= "<br><i> Elle va être intégrée directement<i>";
				}
			}

			if (($img_info[0] * $img_info[1]) > IMAGE_TAILLE) {
				// si la largeur > 4.2 Mpixel on la diminue surtout pour les png qui sont passés au travers de upload.js
				$img = imagecreatefromstring(file_get_contents(TMP_UPLOAD_PATH . $filesname[$i]));
				$new_img = imagescale($img, IMAGE_TAILLE);
				imagejpeg($new_img, TMP_UPLOAD_PATH . 'resized-' . $filesname[$i]);
				imagedestroy($new_img);
				$imageName = 'resized-' . $filesname[$i];
			} else {
				$imageName = $filesname[$i];
			}
			// la catégogie de photo
			if ($first) {
				$cat = $docCatId;
				$first = FALSE;
			} else {
				if ($docCatNext) {
					$cat = $docCatNext;
				} else {
					$cat = $docCatId;
				}
			}

			$options = array('input' => array(
				//'name' => $filesname[$i],
				'name' => $filesnameOK,
				'upload_file' => $imageName,
				'itemtype' => $itemtype,
				'items_id' => $itemID,
				'documentcategories_id' => $cat, // catégogie = Ticket
				'comment' => 'XXX',
			));

			// on déplace le document vers le serveur d'API 
			if (sendFile($imageName)) {
				// si l'envoi a été ok, on supprime le fichier localement
				unlink(TMP_UPLOAD_PATH . $imageName);
			}

			$doc = myAction('Document', $options, POST, $sess_token);

			if (!isset($doc['id']))	logg("addPhoto Probleme (first): " . print_r($doc, true));

			$tmp = explode("-", $doc['message']);
			$test = isset(json_decode(trim($tmp[1]), true)['itemtype']);

			// test du nom du doc
			if ($test || (stripos($tmp[1], $filesnameOK) === FALSE)) {
				// ca a merdé
				$options = array('input' => array(
					'name' => $filesnameOK,
				));
				$newdoc = myAction('Document/' . $doc['id'], $options, PUT, $sess_token);
				logg(" addPhoto  rectif: " . print_r($newdoc, true));
			}

			if (isset($doc['id'])) {
				$out .= "<br><code>Photo ajoutée : " . $filesname[$i] . " </code>";
				$doc_id[] = $doc['id'];
			} else {
				$out .= "<br><code><b> La photo " . $filesname[$i] . " n'a pas été ajoutée </b>Problème inconnu</code>";
			}
		} // delais
	} //for , fichier suivant
	logg('out:' . $out . "\n");
	if ($affiche) echo $out;
	$_SESSION['out'] = $out;
	return $doc_id;
}

function sendFile($file, $url = UPLOAD_API_URL)
{
	//	logg("SendFile md5 to send : " . md5_file(TMP_UPLOAD_PATH . $file));
	$ch = curl_init();
	// send a file
	if (function_exists('curl_file_create')) { // php 5.5+
		$cFile = curl_file_create(TMP_UPLOAD_PATH . $file);
	} else { // 
		$cFile = '@' . realpath(TMP_UPLOAD_PATH . $file);
	}
	$data = array(
		'extra_info' => md5_file(TMP_UPLOAD_PATH . $file),
		'file_contents' => $cFile
	);

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	// output the response
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$post = curl_exec($ch);
	if (curl_errno($ch)) {
		logg('Curl error: ' . curl_error($ch) . "\n");
	}

	// close the session
	curl_close($ch);
	//	logg("SendFile result :" . print_r($post, true) . "\n");
	if ($post === md5_file(TMP_UPLOAD_PATH . $file)) {
		//logg("SendFile result :OK\n");
		return TRUE;
	} else {
		logg("SendFile result :PAS OK\n");
		return FALSE;
	}
}

function correctImageOrientation($filename)
{
	if (function_exists('exif_read_data')) {
		$exif = exif_read_data($filename);
		if ($exif && isset($exif['Orientation'])) {
			$orientation = $exif['Orientation'];
			if ($orientation != 1) {
				$img = imagecreatefromjpeg($filename);
				$deg = 0;
				switch ($orientation) {
					case 3:
						$deg = 180;
						break;
					case 6:
						$deg = 270;
						break;
					case 8:
						$deg = 90;
						break;
				}
				if ($deg) {
					$img = imagerotate($img, $deg, 0);
				}
				// then rewrite the rotated image back to the disk as $filename 
				imagejpeg($img, $filename, 95);
				return 1;
			} // if there is some rotation necessary
		} // if have the exif orientation info
		else {
			return 0;
		}
	} // if function exists      
	else {
		return 0;
	}
}

/* j'aurais bien aimé rajouter ça mais il dit que pel existe pas (je croyais que c'était la librarie)
//$jpeg = new PelJpeg($original_image);
$exif = $jpeg->getExif();


creates copy of $original_image to $new_image, adds watermark to $new_image


$jpeg = new PelJpeg($new_image);
$jpeg->setExif($exif);
header("Content-Type: image/jpeg");
echo $jpeg->getBytes();
*/


function IfeelGood($sess_token, $itemtypeid, $itemtype = 'location', $entity = FALSE, $url_good = "/clients/ifeelgood.html")
{
	if (!(defined('API_URL'))) {
		selectURL();
	}

	if ($entity) {
		//on se positionne sur la bonne entité 
		$options['entities_id'] = $entity;
		$post = myAction('changeActiveEntities', $options, POST, $sess_token);
	}
	//------------------
	//	Mettre le titre en json , désactivé pour le moment
	$titre = json_encode(array(
		'itemtype' => $itemtype,
		'itemtypeID' => $itemtypeid,
		'titre' => 'I Feel Good',
	));
	//---------------------
	//ajout du ticket
	$options = array('input' => array(
		'name' => $titre,
		'requesttypes_id' => '7',
		'content' => 'Yes !',
		'type' => '1',
		'itilcategories_id' => '3', // 3-> I Feel Good
		'status' => '6', // le ticket est resolu //6 clos
	));
	if ($itemtype == 'location') {
		$options['input']['locations_id'] = $itemtypeid;
	}

	$Ticket = myAction('Ticket', $options, POST, $sess_token);

	header("Location: $url_good");
	//echo 'Yes ! I am Happy !';
}

function choose($sess_token, $user)
{
	if (!(defined('API_URL'))) {
		selectURL();
	}

	// table des profiles gest
	$admin = array('10', '17');

	// profile pi_occupant
	$occupant = array('12' => '', '14' => '', '13' => '', '16' => '', '17' => ''); // 12 pi_occupant, 13 pi_occupant_resp, 16 pi_occupant_gest, 17 pi_gest

	echo "<div id='wrapper'><div id='main'><div class='inner'>";
	echo "<body> <h1 style='margin-top: .8em;'>Périmètre de l'intervention</h1>";
	echo 'Vous souhaitez déclarer une intervention, veuillez préalablement définir :';
	echo '<form action="ticket.php" method="get">';


	if (!isset($_GET['entity'])) {
		// sans entity donné	
		echo '<input id="myUser" name="user" type="hidden" value="' . $user . '" >';

		// la catégogie
		echo '<div> Choisir la Catégorie : ';
		$cat = myAction('itilcategory', NULL, GET, $sess_token);
		echo '<select name="cat" size ="1">';
		foreach ($cat as $item) {
			echo '<option value="' . $item['id'] . '">' . $item['name'] . '</option>';
		}
		echo '</select>';
		echo '</div>';

		//1. Liste des entités	
		$entities = myAction('getMyEntities', NULL, GET, $sess_token);
		echo '<div>L\'entité concernée<br>';
		echo '<SELECT size="1">';
		foreach ($entities['myentities'] as $item) {
			echo '<OPTION value=' . $item['id'] . '>' . $item['name'] . ' ---> ' . $item['id'] . '</option>';
		}
		echo '</SELECT></div><br>';

		//2. Selection de l'équipement
		echo '<div>Lieu concerné : ';
		echo '<SELECT name="ept_entity" size="1">';
		$options = array();
		$change = myAction('changeActiveEntities', $options, POST, $sess_token);
		$list_eqpt = myAction('location', NULL, GET, $sess_token);
		foreach ($list_eqpt as $item) {
			$entityTmp = myAction('entity/' . $item['entities_id'], NULL, GET, $sess_token);
			echo '<OPTION value="' . $item['room'] . ';' . $item['entities_id'] . '">' . $entityTmp['name'] . ' ---> ' . $item['name'] . '</option>';
			//echo '<OPTION value='. $item['room'].';'.$item['entities_id'] . '>'.$entityTmp['name'].' ('.$item['entities_id'] .') ---> '.$item['name'].' / '.$item['code_eqpt'].'</option>';
		}
		echo '</SELECT></div><br>';
	} else {
		// avec entity donné
		$options['entities_id'] = $_GET['entity'];
		//$options['is_recursive']='true';
		$change = myAction('changeActiveEntities', $options, POST, $sess_token);

		// récupère liste profiles et token
		$adm_sess = getSession(PI_ADM_TK);
		$options['entities_id'] = $_GET['entity'];
		//$options['is_recursive']='true';
		$change = myAction('changeActiveEntities', $options, POST, $adm_sess);
		$profils = myAction('profile_user', NULL, GET, $adm_sess);
		$users_entity = myAction('user', NULL, GET, $adm_sess);
		killmySession($adm_sess);
		//echo "users_entity: <pre>".print_r($users_entity, true)."</pre>";

		// construction table des user pi_occupant et assimilé
		$users_pi_occupant = array(); // table d'id
		foreach ($users_entity as $item) {
			if (isset($occupant[$item['profiles_id']])) {
				$users_pi_occupant[] = $item['id'];
			}
		}

		// le user
		$profil = myAction('getMyProfiles', NULL, GET, $sess_token);
		$myprofiles = array_column($profil['myprofiles'], 'id');
		//echo "myprofiles: <pre>".print_r($myprofiles, true)."</pre>";

		$diff = array_diff($myprofiles, $admin);
		if ($diff == $myprofiles) {
			// je ne suis dans aucun !	=> je ne suis pas admin ou gest
			echo '<input id="myUser" name="user" type="hidden" value="' . $user . '" >';
		} else {
			echo "<i> -> je suis administrateur <- </i>";
			echo '<div> Choisir le user parmi la liste des occupants : ';
			echo '<select id="myUser" name="user" size ="1">';
			echo '<option value="' . $_GET['user'] . '"> Moi - Attention mon Token va être diffusé!</option>';
			foreach ($users_pi_occupant as $item) {
				$u = myAction('user/' . $item, NULL, GET, $sess_token);
				echo '<option value="' . $u['api_token'] . '">' . $u['name'] . '</option>';
			}
			echo '</select>';
			echo '</div>';
		}

		// la catégogie
		echo '<div> Choisir la Catégorie : ';
		$cat = myAction('itilcategory', NULL, GET, $sess_token);

		echo '<select name="cat" size ="1">';
		foreach ($cat as $item) {
			echo '<option value="' . $item['id'] . '">' . $item['name'] . '</option>';
		}
		echo '</select>';
		echo '</div>';

		// le lieu (location)
		echo '<div>Lieu concerné : ';
		echo '<SELECT name="ept_entity" size="1">';


		$list_eqpt = myAction('location', 'searchText[entities_id]=' . $_GET['entity'], GET, $sess_token);
		$myentity = myAction('entity/' . $_GET['entity'], NULL, GET, $sess_token);
		foreach ($list_eqpt as $item) {
			if ($item['entities_id'] == $_GET['entity']) {
				echo '<OPTION value="' . $item['room'] . ';' . $item['entities_id'] . '">' . $myentity['name'] . ' > ' . $item['name'] . '</option>';
				//echo '<OPTION value='. $item['room'].';'.$item['entities_id'] . '>'.$entityTmp['name'].' ('.$item['entities_id'] .') ---> '.$item['name'].' / '.$item['code_eqpt'].'</option>';
			}
		}
		echo '</SELECT></div><br>';
		//echo 'list_eqpt : <pre>'.print_r($list_eqpt, true).'</pre>';
	}


	//3. Selection du lot technique
	echo '<div>La nature technique de l\'incident :
				<select name="lot" value="">
					<option value="elec">Electricité</option>
					<option value="CVC">CVC</option>
					<option value="eau">Plomberie</option>
					<option value="_asc">ascenseurs</option>
                    <option value="_murs">murs</option>
                    <option value="_sols">sols</option>
                    <option value="_plaf">plafonds</option>
					<option value="_portes">portes</option>
                    <option value="_fenetres">fenêtres</option>
                    <option value="_stores">stores, volets</option>
                    <option value="_espaces">espaces verts</option>
					<option value="VRD">VRD - Espaces Verts</option>
					<option value="_ind">indéterminé</option>
					
				</select>
		</div><br>';
	echo '<div> <input value="Confirmer ce périmètre" type="submit"></div>';
	echo '</form>';
	echo 'Pour annuler fermez simplement cette fenêtre.
			<div style="text-align: right;"><img src="https://portail.pilote.immo/img/piloteimmo4_blanc.png"
				alt="Pilote Immo"
				title="logo"
				style="width: 184px; height: 78px;">
			</div>';
	return 1;
}

function getConfUrl($module, $entity = 0)
{
	if (file_exists('conf/' . $entity . '/' . $module . '.txt')) {
		foreach (FILES as $filename) {
			$url[$filename] = 'conf/' . $entity . '/' . $filename . '.php';
			//logg("+->file to use : " .  $url[$filename] . "\n");
		}
	} else {
		foreach (FILES as $filename) {
			$url[$filename] = 'html/' . $filename . '.php';
			//	logg("+->file to use : " .  $url[$filename] . "\n");
		}
	}
	return $url;
}


function file2pdf($file, $url, $dir = ROOT_DIR . '/tmp/')
{
	// par défaut, le fichier pdf est déposé sur un dossier accessible en web
	$ch = curl_init();
	// send a file
	if (function_exists('curl_file_create')) { // php 5.5+
		$cFile = curl_file_create($file);
	} else { // 
		$cFile = '@' . realpath($file);
	}
	$data = array(
		'extra_info' => md5_file($file),
		'file_contents' => $cFile
	);
	//logg("file2pdf data :" . print_r($data['file_contents']['name'], true) . "\n");


	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	// output the response
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt(
		$ch,
		CURLOPT_HEADERFUNCTION,
		function ($curl, $header) use (&$headers) {
			$len = strlen($header);
			$header = explode(':', $header, 2);
			if (count($header) < 2) // ignore invalid headers
				return $len;
			$headers[strtolower(trim($header[0]))][] = trim($header[1]);
			return $len;
		}
	);

	$post = curl_exec($ch);
	if (curl_errno($ch)) {
		logg('Curl error: ' . curl_error($ch) . "\n");
	}

	// close the session
	curl_close($ch);
	//logg("file2pdf result headers :" . print_r($headers, true) . "\n");

	if (isset($headers['content-type'])) {
		if ($headers['content-type'][0] == 'pdf') {
			logg(" -> write to fille: " . $dir . $headers['filename'][0] . "\n");
			file_put_contents($dir . $headers['filename'][0], $post);
			return array('filename' => $dir . $headers['filename'][0], 'header-ret' => $headers);
		}
	} else {
		return false;
	}
}


function selectLang()
{
	if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
		$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
	} else {
		$lang = "en";
	}

	if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
		$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
	} else {
		$lang = 'en';
	}
	logg("La langue du navigateur est : " . $lang . "\n");
	include('lang.php');
	if ($lang == 'it') $lang = 'fr';
	if ($lang == 'es') $lang = 'fr';
	if (!isset($bd_message[$lang])) $lang = "en";
	$lg = $bd_message[$lang];
	foreach ($lg as $k => $i) {
		$var = $k;
		global $$var;
		$$var = $i;
	}
}


function nettoyerChaine($string, $full = true)
{
	if (!$full) return str_replace(' ', '-', $string);
	$string = str_replace(' ', '-', $string);
	$string = preg_replace('/pratique/[^A-Za-z0-9-]/', '', $string);
	return preg_replace('/pratique/-+/', '-', $string);
}

function getFile($url, $name)
{
	file_put_contents(TMP_DIR . $name, file_get_contents($url));
	return (TMP_DIR . $name);
}

function pre($txt)
{
	echo "<pre>" . print_r($txt, true) . "</pre>";
}

function array_msort($array, $cols)
{
	$colarr = array();
	foreach ($cols as $col => $order) {
		$colarr[$col] = array();
		foreach ($array as $k => $row) {
			$colarr[$col]['_' . $k] = strtolower($row[$col]);
		}
	}
	$eval = 'array_multisort(';
	foreach ($cols as $col => $order) {
		$eval .= '$colarr[\'' . $col . '\'],' . $order . ',';
	}
	$eval = substr($eval, 0, -1) . ');';
	eval($eval);
	$ret = array();
	foreach ($colarr as $col => $arr) {
		foreach ($arr as $k => $v) {
			$k = substr($k, 1);
			if (!isset($ret[$k])) $ret[$k] = $array[$k];
			$ret[$k][$col] = $array[$k][$col];
		}
	}
	return $ret;
}

function array_multi_value($arr)
{
	$res = array();
	foreach ($arr as $k => $it) {
		foreach ($it as $i) {
			if (!in_array($i, $res)) $res[] = $i;
		}
	}
	return $res;
}

function piencode($txt)
{
	// Store the cipher method
	$ciphering = "AES-256-CTR";

	// Use OpenSSl Encryption method
	$iv_length = openssl_cipher_iv_length($ciphering);
	$options = 0;

	// Non-NULL Initialization Vector for encryption
	$encryption_iv = '5463243765760543';

	// Store the encryption key
	$encryption_key = "cxvx123332wxDSFSD987";

	// Use openssl_encrypt() function to encrypt the data
	$encryption = openssl_encrypt(
		$txt,
		$ciphering,
		$encryption_key,
		$options,
		$encryption_iv
	);

	// Display the encrypted string
	logg("Encrypted String: " . $encryption . "\n");

	return $encryption;
}

function pidecode($txt)
{
	// Store the cipher method
	$ciphering = "AES-256-CTR";
	// Non-NULL Initialization Vector for encryption
	$encryption_iv = '5463243765760543';

	// Store the encryption key
	$encryption_key = "cxvx123332wxDSFSD987";

	// Store the encryption key
	// Use OpenSSl Encryption method
	$iv_length = openssl_cipher_iv_length($ciphering);
	$options = 0;

	// Use openssl_decrypt() function to decrypt the data
	$decryption = openssl_decrypt(
		$txt,
		$ciphering,
		$encryption_key,
		$options,
		$encryption_iv
	);
	return $decryption;
}

function jpienc($txt)
{
	return piencode(json_encode($txt));
}

function jpidec($txt)
{
	return json_decode(pidecode($txt), true);
}



function check_user_register($sess_token)
// test conf smartphone
{
	if (is_null($sess_token)) {
		$sess_token = getSession($_SESSION['user']);
		$_SESSION['sess_token'] = $sess_token;
	}

	$fs = myAction('getFullSession', NULL, GET, $sess_token)['session'];

	if (isset($_COOKIE['kit']['ment'])) {
		if (in_array($_COOKIE['kit']['ment'], $fs['glpiactiveentities'])) {
			if (!defined('URL'))
				define('URL', 'https://portail.pilote.immo');

			// on prolonge d'un an les cookies
			setcookie('kit[ment]', $_COOKIE['kit']['ment'], time() + (3600 * 24 * 360), '/clients', URL);
			setcookie('kit[ent]', $_COOKIE['kit']['ent'], time() + (3600 * 24 * 360), '/clients', URL);
			setcookie('kit[nom]', $_COOKIE['kit']['nom'], time() + (3600 * 24 * 360), '/clients', URL);
			setcookie('kit[prenom]', $_COOKIE['kit']['prenom'], time() + (3600 * 24 * 360), '/clients', URL);

			return TRUE;
		}
	}
	return false;
}

function check_sp_conf()
// test conf user token sur smart phone
{
	logg('check_sp_conf: cookie' . print_r($_COOKIE, true));
	if (isset($_COOKIE['kit']['ut'])) {
		$ut = $_COOKIE['kit']['ut'];
		if ($sess_token = getSession($ut)) {
			$_SESSION['user_sp'] = $ut;
			$_SESSION['sess_token_sp'] = $sess_token;

			if (isset($_COOKIE['kit']['nom'])) {
				$_SESSION['u_nom'] = $_COOKIE['kit']['nom'];
			} else {
				$_SESSION['u_nom'] = '';
			}
			if (isset($_COOKIE['kit']['prenom'])) {
				$_SESSION['u_prenom'] = $_COOKIE['kit']['prenom'];
			} else {
				$_SESSION['u_prenom'] = '';
			}
			//logg('check_sp_conf: Session'.print_r($_SESSION, true));

			// on prolonge d'un an les cookies
			if (!defined('URL'))
				define('URL', 'https://portail.pilote.immo');

			setcookie('kit[ut]', $_COOKIE['kit']['ut'], time() + (3600 * 24 * 360), '/', URL);
			if (isset($_COOKIE['kit']['nom'])) setcookie('kit[nom]', $_COOKIE['kit']['nom'], time() + (3600 * 24 * 360), '/', URL);
			if (isset($_COOKIE['kit']['prenom'])) setcookie('kit[prenom]', $_COOKIE['kit']['prenom'], time() + (3600 * 24 * 360), '/', URL);

			return TRUE;
		}
	}

	return false;
}

// savoir si toutes les valeurs d'un tableau sont identiques
function isHomogenous($arr)
{
	$firstValue = current($arr);
	foreach ($arr as $val) {
		if ($firstValue !== $val) {
			return false;
		}
	}
	return true;
}

function confLot()
{
	if (isset($_SESSION['entity_conf']['defaut_lot'])) {
		return $_SESSION['entity_conf']['defaut_lot'];
	} else if ((isset($_SESSION['itemtype'])) && ($_SESSION['itemtype'] == LOGEMENT)) {
		return 'info_lgt';
	} else {
		return '-';
	}
}

function mailerror($txt)
{
	$headers = array(
		'MIME-Version: 1.0',    'Content-Type: text/html; charset="UTF-8";',   'Content-Transfer-Encoding: 7bit',
		'Date: ' . date('r', $_SERVER['REQUEST_TIME']),
		'Message-ID: <' . $_SERVER['REQUEST_TIME'] . md5($_SERVER['REQUEST_TIME']) . '@' . $_SERVER['SERVER_NAME'] . '>',
		'From: support@pilote.immo',   'X-Mailer: PHP v' . phpversion(), 'X-Originating-IP: ' . $_SERVER['SERVER_ADDR']
	);

	mail(
		"support@pilote.immo",
		'WARNING - Kit error',
		'Une erreur inatendue est survenue. Regardez les logg. message :' . $txt,
		implode("\n", $headers)
	);
	// pour que cette fonction mache, il faut que soit deja "include" les élemeents pouur le mail

	return true;
}

function char2html($text)
{
	$text = htmlentities($text, ENT_NOQUOTES, "UTF-8");
	$text = htmlspecialchars_decode($text);
	return $text;
}


function afficheErreur($message = NULL)
{
	echo '
	<!DOCTYPE html>
<html>
	<head> 
		<title>Setup SmartPhone</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable = no"/>
		<link rel="stylesheet" href="/clients/scripts/upload/_css/upload.css" >
		<link rel="stylesheet" href="/clients/scripts/assets/css/main.css">
	</head>
	<body>
	   <div id="wrapper">
		<div id="main">
		   <div class="inner">
				
				  <br><br> ' . $message . '
			</div>
		</div>
	</div>
	</body>
	</html>	
		';
	//   <h1>Merci de scanner de nouveau le QR Code</h1>
}

function exception_error_handler($errno, $errstr, $errfile, $errline)
{
	throw new Exception($errstr);
}

function base64url_encode($data)
{
	return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64url_decode($data)
{
	return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
}



function selectLangFromBowser()
{

	if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {

		$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
	} else {
		$lang = "fr";
	}
	//echo "$lang";

	switch ($lang) {
		case "fr":
			$locale = "fr_FR";
			break;
		case "en":
			$locale = "en_GB";
			break;
		case "de":
			$locale = "de_DE";
			break;
		case "it":
			$locale = "it_IT";
			break;
		case "es":
			$locale = "es_ES";
			break;
		case "ru":
			$locale = "ru_RU";
			break;
		default:
			$locale = "en";
	}
	//echo "locale : $locale <br> \n";
	putenv("LANG=" . $locale);
	setlocale(LC_ALL, $locale);

	$domain = "pitrack";
	//	bindtextdomain($domain, ROOT_DIR."/clients/locale");
	//	bind_textdomain_codeset($domain, 'UTF-8');

	//	textdomain($domain);

}
