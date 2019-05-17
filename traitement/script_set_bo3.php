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
		header('Location: '.$BASE_URL.'pages/set_bo3.php');
		exit();
	}
}
else
{
	$_SESSION['state']="1";
	$_SESSION['message']="Error ! First ID is not filled /!\ Operation aborted /!\ ";
	header('Location: '.$BASE_URL.'pages/set_bo3.php');
	exit();
}
if(isset($_POST['second_id']))
{	
	$second_id=$_POST['second_id'];
	if(verify_input_text('/[^0-9]/', $second_id))
	{
		$_SESSION['state']="1";
		$_SESSION['message']="Field \"Second ID\" allow only numbers";
		header('Location: '.$BASE_URL.'pages/set_bo3.php');
		exit();
	}
}
else
{
	$_SESSION['state']="1";
	$_SESSION['message']="Error ! Second ID is not filled /!\ Operation aborted /!\ ";
	header('Location: '.$BASE_URL.'pages/set_bo3.php');
	exit();
}
if(isset($_POST['third_id']))
{	
	$third_id=$_POST['third_id'];
	if(verify_input_text('/[^0-9]/', $third_id))
	{
		$_SESSION['state']="1";
		$_SESSION['message']="Field \"Third ID\" allow only numbers";
		header('Location: '.$BASE_URL.'pages/set_bo3.php');
		exit();
	}
}
else
{
	$_SESSION['state']="1";
	$_SESSION['message']="Error ! Third ID is not filled /!\ Operation aborted /!\ ";
	header('Location: '.$BASE_URL.'pages/set_bo3.php');
	exit();
}
if(isset($_POST['rules']) && !empty($_POST['rules']))
{	
	$rules=$_POST['rules'];
	if(verify_input_text("/[\"']/", $rules))
	{
		$_SESSION['state']="1";
		$_SESSION['message']="Field \"Config\" does not allow characters : \" '";
		header('Location: '.$BASE_URL.'pages/set_bo3.php');
		exit();
	}
}
else
{
	$_SESSION['state']="1";
	$_SESSION['message']="Error ! Rules is not filled /!\ Operation aborted /!\ ";
	header('Location: '.$BASE_URL.'pages/set_bo3.php');
	exit();
}
if(isset($_POST['ebot_pass']) && !empty($_POST['ebot_pass']))
{	
	$ebot_pass=$_POST['ebot_pass'];
	if(verify_input_text("/[\"']/", $ebot_pass))
	{
		$_SESSION['state']="1";
		$_SESSION['message']="Field \"Password\" does not allow characters : \" '";
		header('Location: '.$BASE_URL.'pages/set_bo3.php');
		exit();
	}
}
else 
{
	$ebot_pass="";
}
if(isset($_POST['first_map']))
{	
	$first_map=$_POST['first_map'];
}
if(isset($_POST['second_map']))
{	
	$second_map=$_POST['second_map'];
}
if(isset($_POST['third_map']))
{	
	$third_map=$_POST['third_map'];
}
if(isset($_POST['season']) && !empty($_POST['season']))
{	
	$chaine=explode(" ", $_POST['season']);
	$season=$chaine[2];
}
else 
{
	$season=NULL;
}
if(isset($_POST['match_mmr']))
{	
	$match_mmr=$_POST['match_mmr'];
}
if(isset($_POST['overtime_status']))
{	
	if($_POST['overtime_status']=="No")
	{
		$overtime_status=0;
	}
	else if($_POST['overtime_status']=="Yes")
	{
		$overtime_status=1;
	}
}
if(isset($_POST['knife']))
{	
	if($_POST['knife']=="No")
	{
		$knife=0;
	}
	else if($_POST['knife']=="Yes")
	{
		$knife=1;
	}
}
if(isset($_POST['overtime_mmr']))
{	
	$overtime_mmr=$_POST['overtime_mmr'];
}
if(isset($_POST['overtime_money']))
{	
	$overtime_money=$_POST['overtime_money'];
	if(verify_input_text('/[^0-9]/', $overtime_money))
	{
		$_SESSION['state']="1";
		$_SESSION['message']="Field \"Overtime Money\" allow only numbers";
		header('Location: '.$BASE_URL.'pages/set_bo3.php');
		exit();
	}
}
elseif(empty($_POST['overtime_money']))
{
	$overtime_money="0";
}
else
{
	$_SESSION['state']="1";
	$_SESSION['message']="Error ! Overtime money is not filled /!\ Operation aborted /!\ ";
	header('Location: '.$BASE_URL.'pages/set_bo3.php');
	exit();
}
if(check_csrf('csrf')==false)
{
	$_SESSION['state']="1";
	$_SESSION['message']="Error CSRF !";
	header('Location: '.$BASE_URL.'pages/set_bo3.php');
	exit();
}
if(!empty($first_id) and !empty($second_id) and !empty($third_id) and !empty($rules) and !empty($overtime_money))
{
	if(($first_id>=$second_id) || ($second_id>=$third_id) || ($first_id >=$third_id))
	{
		$_SESSION['state']="1";
		$_SESSION['message']="Error on ID /!\ Operation aborted /!\ ";
		header('Location: '.$BASE_URL.'pages/set_bo3.php');
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
		header('Location: '.$BASE_URL.'pages/set_bo3.php');
		exit();
	}
	$check_max_id = $BDD_EBOT->query('SELECT MAX(id) FROM matchs');
	while ($donnees_max = $check_max_id->fetch())
	{
		$max_id=$donnees_max['MAX(id)'];
	}
	$check_max_id->closeCursor();
	if($third_id>$max_id)
	{
		$_SESSION['state']="1";
		$_SESSION['message']="Error on third ID (too high) /!\ Operation aborted /!\ ";
		header('Location: '.$BASE_URL.'pages/set_bo3.php');
		exit();
	}
	
	$check_status = $BDD_EBOT->prepare('SELECT id, status, enable FROM matchs WHERE id = ? OR id = ? OR id = ?');
	$check_status->execute(array($first_id, $second_id, $third_id));
	while ($donnees_status = $check_status->fetch())
	{
		if(($donnees_status['status']!= "0")||($donnees_status['enable']!= ""))
		{
			if(empty($donnees_status['enable'])){$donnees_status['enable']="NULL";}
			$_SESSION['state']="1";
			$_SESSION['message']="Error can't update #".$donnees_status['id']." because status = ".$tab_status[$donnees_status['status']]." and enable = ".$tab_enable[$donnees_status['enable']]." /!\ Operation aborted /!\ ";
			header('Location: '.$BASE_URL.'pages/set_bo3.php');
			exit();
		}
	}
	$check_status->closeCursor();
	
	$req1 = $BDD_EBOT->prepare("UPDATE matchs SET rules= ?, config_password= ?, season_id = ?, max_round = ?, config_ot= ?, config_knife_round= ?, overtime_max_round= ?, overtime_startmoney= ? WHERE status=0 AND enable IS NULL AND id= ?");
	$req2 = $BDD_EBOT->prepare("UPDATE maps SET map_name= ? WHERE status=0 AND id= ?");
	
	$req1->execute(array($rules, $ebot_pass, $season, $match_mmr, $overtime_status, $knife, $overtime_mmr, $overtime_money, $first_id));
	$req1->closeCursor();
	$req2->execute(array($first_map, $first_id));
	$req2->closeCursor();
		
	$req1->execute(array($rules, $ebot_pass, $season, $match_mmr, $overtime_status, $knife, $overtime_mmr, $overtime_money, $second_id));
	$req1->closeCursor();
	$req2->execute(array($second_map, $second_id));
	$req2->closeCursor();
		
	$req1->execute(array($rules, $ebot_pass, $season, $match_mmr, $overtime_status, $knife, $overtime_mmr, $overtime_money, $third_id));
	$req1->closeCursor();
	$req2->execute(array($third_map, $third_id));
	$req2->closeCursor();

	$_SESSION['state']="2";
	$_SESSION['message']="Maps ".$first_map.", ".$second_map.", ".$third_map." have been updated on matches #".$first_id." to #".$third_id;
	$ip = get_ip();
	$action=$_SESSION['message'];
	store_action($action, $ip, $BDD_ADMINAFK);
}
else 
{
	$_SESSION['state']="1";
	$_SESSION['message']="All fields must be filled ! /!\ Operation aborted /!\ ";
}
	header('Location: '.$BASE_URL.'pages/set_bo3.php');
	exit();

?>