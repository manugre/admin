
<?php
/********************
Création d'un client dans glpi / kit / clients /dev
********************/
if (!(defined('ROOT_DIR'))) {
	define('ROOT_DIR', $_SERVER['DOCUMENT_ROOT']); // répertoire racine
}

// on intègre les fonctions de base GLPI 
include ROOT_DIR . 'clients/scripts/include/glpi_function.php';

define('DEBUG', false);
define('LOG_FILE',  DOC_ROOT.'glpi/log/add_client.log');
$log=fopen(LOG_FILE, "a");

if (!isset($_GET['pass'])) exit ('utilisaation non valide');
/****************************
 * 
 * A modifier ici
 * 
 */
// configuration
selectURL('kit'); // kit ou clients
$client_itemtype=LOGEMENT; /// => PluginGenericobjectLogement
$client_nom_itemtype="Logement";

$client_nom='Valmorel Locations Immobilier';
$client_code='VALMOREL_LOCATION_IMMO'; // nom entité niveau 4
$client_entite[0]='GESTION PLEINE'; // nom entité niveau 5
//$client_entite[1]='CONCIERGEIE'; // nom entité niveau 5

$client_zoom_l4='14'; // Zoom de la carte entité niveau 4
$client_zoom_l5='14'; // Zoom de la carte entité niveau 5


$client_prefix_username='VLI';
$client_entite_mere='267'; // id entité de niveau 3 // Zermatt 

$client_mail='info@valmorel-location-immobilier.com';
$client_tel='+334 79 09 05 88 ';

$client_OT='1'; // 0 ou 1 si sur Dégradation : "J'autorise l'OT à obtenir mon adresse mail"
$client_AG='1'; // 0 ou 1  si sur Dégradation : "J'autorise l'agence à intervenir en mon abscence"
$client_CI='1'; // 0 ou 1 : 1 = checkin obligatoire (check_checkin activé)
$client_KIT='1'; //0 pas proposé, 1 proposé, 2 choix par defaut : 'Je souhaite resté informé'

//$client_avis="https://search.google.com/local/writereview?placeid=ChIJ3S-JXmauEmsRUcIaWtf4MzE" ;  // url du site où donner son avis à la fin d'un check out$client_avis="";
//$client_ifg='https://www.les2alpes.com'; // URL IFeelGood ou info_touristique
$client_ifg='https://www.valmorel.com/agenda/les-animations/';

$client_resa_mail_validator=''; // mail de validateur d'une resa

/*
	$client_notif_degradation="Bonjour,
Nous vous remercions d'avoir signalé le problème.
Vous pouvez suivre à tout moment l'avancée de sa résolution en cliquant sur le lien suivant :
##lien##
La Communauté Coeur de Tarentaise vous souhaite une agréable journée.";
*/
$client_notif_degradation="Bonjour, RETOUR
Nous vous remercions pour votre signalement. RETOUR
Vous pouvez suivre à tout moment l’avancée de votre réclamation en cliquant sur le lien suivant : RETOUR
##lien## RETOUR
En vous souhaitant un agréable séjour avec Valmorel Location RETOUR
RETOUR RETOUR
Hello, RETOUR
Thank you for your request. RETOUR
You can follow the progress of your complaint at any time by clicking on the following link : RETOUR
##lien##  RETOUR
Enjoy your stay with Valmorel Location RETOUR";

/********************************
 * DEBUT
 * ****************************** */

$sess_token=getSession(USER_ADM_TK);


/************
 * Quelques tests
 * ********* */

$get=myAction('Entity', NULL, GET, $sess_token);

foreach($get as $i){
	if ($i['name']==$client_code) {
		exit('Nom deja pris ! :'.$client_code);
	}
	if ($i['id']==$client_entite_mere) $ok=1;
}
if (!isset($ok)) exit ("client_entite_mere n'existe pas !: ".$client_entite_mere);


$get=myAction('User', NULL, GET, $sess_token);

foreach($get as $i){
	if (strpos ($i['name'], $client_prefix_username)){
		exit('Nom deja pris ! :'.$client_prefix_username);
	}
}


 /******************
  * On lance
  */
$options = array(
	'entities_id' => $client_entite_mere,
	);
$post = myAction('changeActiveEntities', $options, POST, $sess_token);
logg('Select entity level 3 result :'.print_r($post, true));
//echo 'Select entity level 3 result :'.print_r($post, true);
echo 'Select entity level 3 result : '.print_r($post, true)."<br>\n";


// création des entités
$options = array('input'=>array(
	'name'=>$client_code,
	'phonenumber'=>$client_tel,
	'email'=>$client_mail,
	'mailing_signature'=>str_replace ('RETOUR', '\r\n\r', $client_notif_degradation),
	'altitude'=>$client_zoom_l4,
	));

$post = myAction('Entity', $options, POST, $sess_token);
logg('Creat entity level 4 result :'.print_r($post, true));
//echo 'Creat entity level 4 result :'.print_r($post, true);
echo 'Creat entity level 4 result :'.$post['message']."<br>\n";
$mID=$post['id'];

// mise à jour du "level"
$options = array('input'=>array(
		'items_id'=>$mID,
		'itemtype'=>'Entity',
		'niveaufield'=>'4',
		));
	
$post = myAction('PluginFieldsEntitypiloteimmoentite/', $options, POST, $sess_token);
logg('Creat PluginFieldsEntitypiloteimmoentite level 4 result :'.print_r($post, true));
//echo 'Creat entity level 4 result :'.print_r($post, true);
echo 'Creat PluginFieldsEntitypiloteimmoentite level 4 result :'.$post['message']."<br>\n";



$options = array(
	'entities_id' => $mID,
);
$post = myAction('changeActiveEntities', $options, POST, $sess_token);


foreach ($client_entite as $l5) {
	$options = array('input' => array(
		'name' => $l5,
		'state' => json_encode(array($client_itemtype => $client_nom_itemtype)),
		'altitude' => $client_zoom_l5,
	));

	$post = myAction('Entity', $options, POST, $sess_token);
	logg('Creat entity level 5 result :' . print_r($post, true));
	//echo 'Creat entity level 5 result :' . print_r($post, true);
	echo 'Creat entity level 5 result :' . $post['message'] . "<br>\n";

	$id=$post['id'];

	// mise à jour du "level"
		$options = array('input' => array(
			'items_id'=>$id,
			'itemtype'=>'Entity',
			'niveaufield' => '5',
		));

		$post = myAction('PluginFieldsEntitypiloteimmoentite/' . $FieldEntiteID, $options, POST, $sess_token);
		logg('Creat PluginFieldsEntitypiloteimmoentite level 5 result :' . print_r($post, true));
		//echo 'Creat entity level 4 result :'.print_r($post, true);
		echo 'Creat PluginFieldsEntitypiloteimmoentite level 5 result :' . $post['message'] . "<br>\n";
}


// création des groupes
$options = array('input'=>array(
	'name'=>$client_prefix_username.'_users',
	'comment'=>'Groupe des users de '.$client_nom,
	'is_recursive'=>1,
	'is_task'=>1,
	));

$post = myAction('Group', $options, POST, $sess_token);
logg('Creat Group:'.print_r($post, true));
//echo 'Creat Group result :'.print_r($post, true);
echo 'Creat Group result :'.$post['message']."<br>\n";


$gID=$post['id'];

// création des utilisateurs
//1. Pi_visiteur
$visitorUT=str_shuffle(sha1(time()));
$options = array('input'=>array(
	'name'=>$client_prefix_username.'_00',
	'realname'=>'Occupant',
	'firstname'=>'_',
	'registration_number'=>'VISITEUR',
	'api_token'=>$visitorUT,
	'personal_token'=>str_shuffle(sha1(time())),
	'profiles_id'=>31, // profile = kit_visiteur
	'is_active'=>1,
	'groups_id' => $gID,
	));

$post = myAction('User', $options, POST, $sess_token);
logg('Creat User Visiteur:'.print_r($post, true));
//echo 'Creat User Visiteur:'.print_r($post, true);
echo 'Creat User Visiteur:'.$post['message']."<br>\n";

$visitorID=$post['id'];

$options = array('input'=>array(
	'users_id' => $visitorID,
	'profiles_id' => 31,
	'entities_id' => $mID,
	'is_recursive' => true,
	'groups_id' => $gID,
	));
$post=myAction('profile_user', $options, POST, $sess_token);
logg('Creat profile_user:'.print_r($post, true));
//echo 'Creat profile_user:'.print_r($post, true);
echo 'Creat profile_user:'.$post['message']."<br>\n";

$options = array('input'=>array(
	'users_id' => $visitorID,
	'groups_id' => $gID,
	));
$post=myAction('Group_User', $options, POST, $sess_token);
logg('Creat Group_User:'.print_r($post, true));
//echo 'Creat Group_User:'.print_r($post, true);
echo 'Creat Group_User:'.$post['message']."<br>\n";

//2. le User compte client
$mainUserUT=str_shuffle(sha1(time()));
$options = array('input'=>array(
	'name'=>$client_prefix_username.'_1',
	'realname'=>$client_nom,
	'firstname'=>'_',
	'registration_number'=>'CLIENT',
	'api_token'=>$mainUserUT,
	'personal_token'=>str_shuffle(sha1(time())),
	'profiles_id'=>32, // profile = kit_logement
	'is_active'=>1,
	'groups_id' => $gID,
	));

$post = myAction('User', $options, POST, $sess_token);
logg('Creat User Visiteur:'.print_r($post, true));
//echo 'Creat User Visiteur:'.print_r($post, true);
echo 'Creat User Visiteur:'.$post['message']."<br>\n";

$mainUserID=$post['id'];

$options = array('input'=>array(
	'users_id' => $mainUserID,
	'profiles_id' => 32,
	'entities_id' => $mID,
	'is_recursive' => true,
	));
$post=myAction('profile_user', $options, POST, $sess_token);
logg('Creat profile_user(32):'.print_r($post, true));
echo 'Creat profile_user(32):'.$post['message']."<br>\n";

$options = array('input'=>array(
	'users_id' => $mainUserID,
	'profiles_id' => 40, // kit+gest
	'entities_id' => $mID,
	'is_recursive' => true,
	));
$post=myAction('profile_user', $options, POST, $sess_token);
logg('Creat profile_user(40):'.print_r($post, true));
echo 'Creat profile_user(40):'.$post['message']."<br>\n";


$options = array('input'=>array(
	'users_id' => $mainUserID,
	'groups_id' => $gID,
	));
$post=myAction('Group_User', $options, POST, $sess_token);
logg('Creat Group_User:'.print_r($post, true));
//echo 'Creat Group_User:'.print_r($post, true);
echo 'Creat Group_User:'.$post['message']."<br>\n";


// suppression du profil kit_visiteur du user Main
$get=myAction('User/'.$mainUserID.'/profile_user', NULL, GET, $sess_token);
$profils=array_column($get, 'id', 'profiles_id');
if (isset($profils['31'])){
	$del=myAction('profile_user/'.$profils['31'], NULL, DELETE, $sess_token);
	logg('DEL Profile Visteur:'.print_r($del, true));
	echo 'DEL Profile Visteur:'.print_r($del, true)."<br>\n";
}


//3. Paramétrer l'entité de niveau 4
$parm=array(
	'mail'=>array('from_name'=>$client_nom),
	'username'=>$client_prefix_username,
	'ut' =>piencode($mainUserUT),
	);

if ($client_OT) $parm['OT']=1;
if ($client_AG) $parm['AG']=1;
if ($client_CI) $parm['CI']=1;
if ($client_avis) $parm['avis']=$client_avis;
if ($client_ifg) $parm['ifg']=$client_ifg;
if ($client_resa_mail_validator) $parm['resa_valid']=$client_resa_mail_validator;


$options = array('input'=>array(
	'website'=>json_encode($parm),
	'fax'=>'{"user":["'.$visitorUT.'"]}',
	));

$put = myAction('Entity/'.$mID, $options, PUT, $sess_token);
logg('MAJ entity level 4 result :'.print_r($put, true));
//echo 'MAJ entity level 4 result :'.print_r($put, true);
echo 'MAJ entity level 4 result :'.print_r($put, true)."<br>\n";

echo "<br>END<br>";

?>
