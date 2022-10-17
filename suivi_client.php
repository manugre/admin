<?php
if (!(defined('ROOT_DIR'))) {
	define('ROOT_DIR', $_SERVER['DOCUMENT_ROOT']); // répertoire racine
}

// on intègre les fonctions de base GLPI 
include ROOT_DIR . 'clients/scripts/include/glpi_function.php';

if ((!isset($_GET['pass'])) && ($_GET['pass']!='432fsdfsd')) exit ('impossible');

/**********
 * 
 * début
 */

selectURL('kit');
$session=getSession(PI_ADM_TK);

$all_entities=myAction('Entity', NULL, GET,  $session);

$data=array();
foreach($all_entities as $mEntity){
    if ($mEntity['level']==4){
        $client=$mEntity['name'];


        $options['entities_id'] = $mEntity['id'];
        $options['is_recursive'] = "true";
        $post = myAction('changeActiveEntities', $options, POST, $session);

        $tickets=myAction('Ticket',NULL, GET, $session);

        $lgt=myAction(LOGEMENT, NULL, GET, $session);

        $location=myAction('Location', NULL, GET, $session);

        $resa=myAction('Reservation', NULL, GET, $session);
      
        
        $data[]=array(
            'Client'=> $client,
            'Nb Dégradation' =>count($tickets),
            'Nb Dégradation Closes' =>deg_close($tickets),
            'Nb Dégradation CE mois' =>deg_cemois($tickets),
            'Nb Dégradation mois dernier' =>deg_prevmois($tickets),

            'Nb Logement en contrat'=> count($lgt),
            'Nb Logement activé'=> lgt_actif($lgt),
            'Nb Emplacement en contrat'=> count($location),
            'Nb Emplacement activé'=> lieu_actif($location),

            'Nb Résa CE mois'=>cemois_resa($resa),
            'Nb Résa mois dernier'=>prev_resa($resa),
            'Nb Résa Total'=>count($resa),

        );
       // break;
    }
    
}
//pre($data);
$out='<table>';
$head=1;
foreach($data as $client){
    $out.='<tr>';
    foreach($client as $k=>$col){
        if ($head) {
            $out.='<td>'.$k.'</td>';
        }
        else {
            $out.='<td>'.$col.'</td>';
        }
    }
    $out.='</tr>';
    $head=0;
}
$out.='</table>';
afficheErreur($out);
//echo $out;

/**********
 * fin
 * 
 */
function lgt_actif($all){
    $i=0;
    foreach($all as $item){
        if ($item['code_pi']!='-') $i++;
    }
    return $i;
}

function lieu_actif($all){
    $i=0;
    foreach($all as $item){
        if ($item['room']!='-') $i++;
    }
    return $i;
}

function deg_close($all){
    $i=0;
    foreach($all as $item){
        if ($item['status']>3) $i++;
    }
    return $i;
}

function deg_cemois($all){
    $i=0;
    $cemois=date("m");
    foreach($all as $item){
        $mois = date("m", strtotime($item['date']));
        if ($mois==$cemois) $i++;
    }
    return $i;
}

function deg_prevmois($all){
    $i=0;
    $prev=date("m")-1;
    foreach($all as $item){
        $mois = date("m", strtotime($item['date']));
        if ($mois==$prev) $i++;
    }
    return $i;
}

function prev_resa($all){
    $i=0;
    $prev=date("m") -1;
    foreach($all as $item){
        $r_mois = date("m", strtotime($item['begin']));

        if ($r_mois==$prev) $i++;
    }
    return $i;
}
function cemois_resa($all){
    $i=0;
    $cemois=date("m");
    foreach($all as $item){
        $r_mois = date("m", strtotime($item['begin']));

        if ($r_mois==$cemois) $i++;
    }
    return $i;
}
?>
