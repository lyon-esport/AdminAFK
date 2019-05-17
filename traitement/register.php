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
include_once 'csrf.php';
include_once "check_input.php";
include_once 'save_log.php';
include_once 'check_ip.php';

session_start();
$result_user = check_user($BDD_ADMINAFK, $_SESSION['login']);
if (!isset($_SESSION['login']) || ($result_user['login']!=$_SESSION['login']))
{
    $_SESSION['state']="1";
	$_SESSION['message']="You must be logged in to access this page";
	header('Location: '.$BASE_URL.'index.php');
	exit();
}
if(isset($_POST['login']))
{	
	$login=$_POST['login'];
	if(verify_input_text("/[\"']/", $login))
	{
		$_SESSION['state']="1";
		$_SESSION['message']="Field \"Login\" does not allow characters : \" '";
		header('Location: '.$BASE_URL.'pages/add_admin.php');
		exit();
	}
}
else
{
	$_SESSION['state']="1";
	$_SESSION['message']="Error !";
	header('Location: '.$BASE_URL.'pages/add_admin.php');
	exit();
}
if(isset($_POST['level']))
{	
	if($_POST['level']=="Super-Admin")
	{
		$level=1;
	}
	else if($_POST['level']=="Admin")
	{
		$level=2;
	}
}
if(isset($_POST['pass']))
{	
	$pass=$_POST['pass'];
	if(verify_input_text("/[\"']/", $pass))
	{
		$_SESSION['state']="1";
		$_SESSION['message']="Field \"Password\" does not allow characters : \" '";
		header('Location: '.$BASE_URL.'pages/add_admin.php');
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
		header('Location: '.$BASE_URL.'pages/add_admin.php');
		exit();
	}
}
if(check_csrf("csrf")==false)
{
	$_SESSION['state']="1";
	$_SESSION['message']="Error CSRF !";
	header('Location: '.$BASE_URL.'pages/add_admin.php'); 
	exit();
}
$ip = get_ip();

if(!empty($login) and !empty($level) and !empty($pass) and !empty($pass_confirm))
{
	if($pass === $pass_confirm)
	{
		try 
		{
			$pass_hache = password_hash($pass, PASSWORD_DEFAULT);
			$req = $BDD_ADMINAFK->prepare('INSERT INTO users(login, password, level, created_at, created_by, update_at, update_by, last_login, last_ip) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)');
			$req->execute(array($login, $pass_hache, $level, date(' Y-m-d H:i:s'), $_SESSION['login'], date(' Y-m-d H:i:s'), $_SESSION['login'], date(' Y-m-d H:i:s'), $ip));
			$_SESSION['state']="2";
			$_SESSION['message']="User ".$login." was created !";
			$action=$_SESSION['message'];
			store_action($action, $ip, $BDD_ADMINAFK);
		}
		catch(PDOException $e)
		{
			error_log($e -> getMessage());
			switch($e->getCode())
			{
				case 23000:
					$_SESSION['state']="1";
					$_SESSION['message']= "User ".$login." already exists";
					break;
				default:
					$_SESSION['state']="1";
					$_SESSION['message']= "An error has occured : ".$e->getCode();
					break;
			}
		}
	}
	else
	{
		$_SESSION['state']="1";
		$_SESSION['message']="Passwords are not the same !";
	}
}
else 
{
	$_SESSION['state']="1";
	$_SESSION['message']="All fields must be filled !";
}
	header('Location: '.$BASE_URL.'pages/add_admin.php');
	exit();
?>