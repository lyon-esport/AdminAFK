<?php
// ---------------------------------------------------------------------------- 
// Copyright © Lyon e-Sport, 2018
// 
// Contributeur(s):
//     * Ortega Ludovic - ludovic.ortega@lyon-esport.fr
// 
// Ce logiciel, AdminAFK, est un programme informatique servant à administrer 
// et gérer un tournoi CS:GO avec eBot et Toornament.l]. 
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
include_once 'connect_bdd.php';
include_once 'verif_user.php';
include_once 'csrf.php';
include_once 'check_ip.php';
include_once 'save_log.php';

session_start();
$result_user = check_user($BDD_ADMINAFK, $_SESSION['login']);
if (!isset($_SESSION['login']) || ($result_user['login']!=$_SESSION['login']))
{
    $_SESSION['state']='1';
	$_SESSION['message']="You must be Super-Admin to have access to this";
	header('Location: '.$BASE_URL.'index.php');
	exit();
}
if ($result_user['login']==$_SESSION['login'])
{
	if($result_user['level']>1)
	{
		$_SESSION['state']='1';
		$_SESSION['message']="You must be Super-Admin to have access to this";
		header('Location: '.$BASE_URL.'index.php');
		exit();
	}
}
if(isset($_POST['login']))
{	
	$login_user=$_POST['login'];
	if(verify_input_text("/[\"']/", $login_user))
	{
		$_SESSION['state']="1";
		$_SESSION['message']="Field \"Login\" does not allow characters : \" '";
		header('Location: '.$BASE_URL.'pages/edit_admin.php');
		exit();
	}
}
else
{
	$_SESSION['state']='1';
	$_SESSION['message']="You need to choose who edit before that";
	header('Location: '.$BASE_URL.'pages/edit_admin.php');
	exit();
}
if(isset($_POST['level']))
{	
	if($_POST['level']=="Super-admin")
	{
		$level_user="1";
	}
	else
	{
		$level_user="2";
	}
}
if(isset($_POST['pass']))
{	
	$pass=$_POST['pass'];
	if(verify_input_text("/[\"']/", $pass))
	{
		$_SESSION['state']="1";
		$_SESSION['message']="Field \"Password\" does not allow characters : \" '";
		header('Location: '.$BASE_URL.'pages/edit_admin.php');
		exit();
	}
}
if(isset($_POST['pass_confirm']))
{	
	$pass_confirm=$_POST['pass_confirm'];
	if(verify_input_text("/[\"']/", $pass_confirm))
	{
		$_SESSION['state']="1";
		$_SESSION['message']="Field \"Confirm Password\" does not allow characters : \" '";
		header('Location: '.$BASE_URL.'pages/edit_admin.php');
		exit();
	}
}
if(check_csrf("csrf")==false)
{
	$_SESSION['state']="1";
	$_SESSION['message']="Error CSRF !";
	header('Location: '.$BASE_URL.'pages/edit_admin.php');
	exit();
}
if($pass !==$pass_confirm)
{
	$_SESSION['state']='1';
	$_SESSION['message']="Password and Confirm Password are not the same";
	header('Location: '.$BASE_URL.'pages/edit_admin.php');
	exit();
}
else
{
	$pass_hache = password_hash($pass, PASSWORD_DEFAULT);
	$ip = get_ip();
	try 
	{
		$req = $BDD_ADMINAFK->prepare('UPDATE users SET password = ?, level = ?, update_by = ?, update_at = ? WHERE login = ?');
		$req->execute(array($pass_hache, $level_user, $_SESSION['login'], date(' Y-m-d H:i:s'), $login_user));
		$req->closeCursor();
	}
	catch(PDOException $e)
	{
		print "Erreur ! : " . $e->getMessage() . "<br/>";
		die();
	}
	$_SESSION['state']='2';
	$_SESSION['message']="User ".$login_user." updated";
	$action=$_SESSION['message'];
	store_action($action, $ip, $BDD_ADMINAFK);
	header('Location: '.$BASE_URL.'pages/edit_admin.php');
	exit();
}
?>