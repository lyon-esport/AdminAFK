<?php
// ---------------------------------------------------------------------------- 
// Copyright © Lyon e-Sport, 2018
// 
// Contributeur(s):
//     * Ortega Ludovic - ludovic.ortega@lyon-esport.fr
// 
// Ce logiciel, AdminAFK, est un programme informatique servant à administrer 
// et gérer un tournoi CS:GO avec eBot et Toornament.
// 
// Ce logiciel est régi par la licence CeCILL soumise au droit français et
// respectant les principes de diffusion des logiciels libres. Vous pouvez
// utiliser, modifier et/ou redistribuer ce programme sous les conditions
// de la licence CeCILL telle que diffusée par le CEA, le CNRS et l'INRIA 
// sur le site "http://www.cecill.info".
// 
// En contrepartie de l'accessibilité au code source et des droits de copie,
// de modification et de redistribution accordés par cette licence, il n'est
// offert aux utilisateurs qu'une garantie limitée.  Pour les mêmes raisons,
// seule une responsabilité restreinte pèse sur l'auteur du programme,  le
// titulaire des droits patrimoniaux et les concédants successifs.
// 
// A cet égard  l'attention de l'utilisateur est attirée sur les risques
// associés au chargement,  à l'utilisation,  à la modification et/ou au
// développement et à la reproduction du logiciel par l'utilisateur étant 
// donné sa spécificité de logiciel libre, qui peut le rendre complexe à 
// manipuler et qui le réserve donc à des développeurs et des professionnels
// avertis possédant  des  connaissances  informatiques approfondies.  Les
// utilisateurs sont donc invités à charger  et  tester  l'adéquation  du
// logiciel à leurs besoins dans des conditions permettant d'assurer la
// sécurité de leurs systèmes et ou de leurs données et, plus généralement, 
// à l'utiliser et l'exploiter dans les mêmes conditions de sécurité. 
// 
// Le fait que vous puissiez accéder à cet en-tête signifie que vous avez 
// pris connaissance de la licence CeCILL, et que vous en avez accepté les
// termes.
// ----------------------------------------------------------------------------

include_once '../config/config.php';
include_once 'check_config.php';
include_once 'connect_bdd.php';
include_once 'verif_user.php';
include_once 'check_ip.php';
include_once 'save_log.php';
include_once 'csrf.php';
include_once "check_input.php";
include_once 'ebot_status.php';

session_start();
$result_user = check_user($BDD_ADMINAFK, $_SESSION['login']);
if (!isset($_SESSION['login']) || ($result_user['login']!=$_SESSION['login']))
{
    $_SESSION['state']="1";
	$_SESSION['message']="You must be logged in to access this page";
	header('Location: '.$BASE_URL.'index.php');
	exit();
}
if(isset($_POST['first_id']))
{	
	$first_id=$_POST['first_id'];
	if(verify_input_text('/[^0-9]/', $first_id))
	{
		$_SESSION['state']="1";
		$_SESSION['message']="Field \"First ID\" allow only numbers";
		header('Location: '.$BASE_URL.'pages/start_map.php');
		exit();
	}
}
else
{
	$_SESSION['state']="1";
	$_SESSION['message']="Error ! /!\ Operation aborted /!\ ";
	header('Location: '.$BASE_URL.'pages/start_map.php');
	exit();
}	
if(isset($_POST['last_id']))
{	
	$last_id=$_POST['last_id'];
	if(verify_input_text('/[^0-9]/', $last_id))
	{
		$_SESSION['state']="1";
		$_SESSION['message']="Field \"Last ID\" allow only numbers";
		header('Location: '.$BASE_URL.'pages/start_map.php');
		exit();
	}
}
if(isset($_POST['server']))
{	
	$chaine=explode(" ", $_POST['server']);
	$server=$chaine[2];
}
else
{
	$_SESSION['state']="1";
	$_SESSION['message']="You need to add server on eBot ! /!\ Operation aborted /!\ ";
	header('Location: '.$BASE_URL.'pages/start_map.php');
	exit();
}
if(check_csrf('csrf')==false)
{
	$_SESSION['state']="1";
	$_SESSION['message']="Error CSRF !";
	header('Location: '.$BASE_URL.'pages/start_map.php');
	exit();
}
if($first_id>$last_id)
{
	$_SESSION['state']="1";
	$_SESSION['message']="Error ID /!\ Operation aborted /!\ ";
	header('Location: '.$BASE_URL.'pages/start_map.php');
	exit();
}
$check_min_id = $BDD_EBOT->query('SELECT MIN(id) FROM matchs');
while ($donnees_min = $check_min_id->fetch())
{
	$min_id=$donnees_min['MIN(id)'];
}
$check_min_id->closeCursor();
if($first_id<$min_id)
{
	$_SESSION['state']="1";
	$_SESSION['message']="Error on first ID (too few) /!\ Operation aborted /!\ ";
	header('Location: '.$BASE_URL.'pages/start_map.php');
	exit();
}
$check_max_id = $BDD_EBOT->query('SELECT MAX(id) FROM matchs');
while ($donnees_max = $check_max_id->fetch())
{
	$max_id=$donnees_max['MAX(id)'];
}
$check_max_id->closeCursor();
if($last_id>$max_id)
{
	$_SESSION['state']="1";
	$_SESSION['message']="Error on last ID (too high) /!\ Operation aborted /!\ ";
	header('Location: '.$BASE_URL.'pages/start_map.php');
	exit();
}

$check_status = $BDD_EBOT->prepare('SELECT id, status FROM matchs WHERE id BETWEEN ? AND ?');
$check_status->execute(array($first_id, $last_id));
while ($donnees_status = $check_status->fetch())
{
	if((intval($donnees_status['status'])>0)&&(intval($donnees_status['status'])<12))
	{
		$_SESSION['state']="1";
		$_SESSION['message']="Error can't start #".$donnees_status['id']." because status = ".$tab_status[$donnees_status['status']]." /!\ Operation aborted /!\ ";
		header('Location: '.$BASE_URL.'pages/start_map.php');
		exit();
	}
}
$check_status->closeCursor();

$nb_match = intval($last_id) - intval($first_id) + 1;
$p = 0;
$get_serv = $BDD_EBOT->prepare('SELECT id, ip FROM servers WHERE id >= :idstart LIMIT :maxserv');
$get_serv->bindValue(':idstart', $server, PDO::PARAM_STR);
$get_serv->bindValue(':maxserv', $nb_match, PDO::PARAM_INT);
$get_serv->execute();
while ($donnees_serv = $get_serv->fetch())
{
	$serv_list_ip[$p] = $donnees_serv['ip'];
	$serv_list_id[$p] = $donnees_serv['id'];
	$p++;
}
$get_serv->closeCursor();

if(count($serv_list_id)!=$nb_match)
{
	$_SESSION['state']="1";
	$_SESSION['message']="Error you try to start ".$nb_match." matches with ".count($serv_list_id)." servers /!\ Operation aborted /!\ ";
	header('Location: '.$BASE_URL.'pages/start_map.php');
	exit();
}

$check_server_busy = $BDD_EBOT->prepare('SELECT id, status, server_id FROM matchs WHERE server_id = ?');

for($s=0; $s<count($serv_list_id);$s++)
{
	$check_server_busy->execute(array($serv_list_id[$s]));
	while ($donnees_server_busy = $check_server_busy->fetch())
	{
		if((intval($donnees_server_busy['status'])>0)&&(intval($donnees_server_busy['status'])<12))
		{
			$_SESSION['state']="1";
			$_SESSION['message']="Error can't start because server ".$donnees_server_busy['server_id']." is already used by match #".$donnees_server_busy['id']." /!\ Operation aborted /!\ ";
			header('Location: '.$BASE_URL.'pages/start_map.php');
			exit();
		}
	}
	$check_server_busy->closeCursor();
}

$req1 = $BDD_EBOT->prepare('UPDATE matchs SET ip = ?, server_id = ? WHERE id = ?');
for($i=0; $i<$nb_match; $i++)
{
	$current_id = $first_id + $i;
	$req1->execute(array($serv_list_ip[$i], $serv_list_id[$i], $current_id));
	$req1->closeCursor();
}
$req1->closeCursor();

$req2 = $BDD_EBOT->prepare('UPDATE matchs SET status = 1, enable = 1 WHERE id = ?');
for($r=0; $r<$nb_match; $r++)
{
	$current_id = $first_id + $r;
	$req2->execute(array($current_id));
	$req2->closeCursor();
}
$req2->closeCursor();

$_SESSION['state']="2";
$_SESSION['message']="#".$first_id." to #".$last_id." have been started on server #".$serv_list_id[0]." to #".$serv_list_id[$nb_match-1];

$ip = get_ip();
$action=$_SESSION['message'];
store_action($action, $ip, $BDD_ADMINAFK);

header('Location: '.$BASE_URL.'pages/start_map.php');
exit();
?>