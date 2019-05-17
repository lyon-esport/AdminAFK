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
include_once 'check_ip.php';
include_once 'csrf.php';
include_once "check_input.php";

session_start();

if(isset($_POST['login']))
{	
	$login=$_POST['login'];
	if(verify_input_text("/[\"']/", $login))
	{
		$_SESSION['state']="1";
		$_SESSION['message']="Field \"Login\" does not allow characters : \" '";
		header('Location: '.$BASE_URL.'admin.php');
		exit();
	}
}
else
{
	$_SESSION['state']="1";
	$_SESSION['message']="Error !";
	header('Location: '.$BASE_URL.'admin.php');
	exit();
}
if(isset($_POST['pass']))
{	
	$pass=$_POST['pass'];
	if(verify_input_text("/[\"']/", $pass))
	{
	$_SESSION['state']="1";
	$_SESSION['message']="Field \"Password\" does not allow characters : \" '";
	header('Location: '.$BASE_URL.'admin.php');
	exit();
	}
}
if(check_csrf('csrf')==false)
{
	$_SESSION['state']="1";
	$_SESSION['message']="Error CSRF !";
	header('Location: '.$BASE_URL.'admin.php');
	exit();
}
if(!empty($login) and !empty($pass))
{
	try 
	{
		$req = $BDD_ADMINAFK->prepare('SELECT login, password FROM users WHERE login = BINARY ?');
		$req->execute(array($login));
		$resultat = $req->fetch();
		$req->closeCursor();
	}
	catch(PDOException $e)
	{
		print "Erreur ! : " . $e->getMessage() . "<br/>";
		die();
	}

	$isPasswordCorrect = password_verify($pass, $resultat['password']);

	if (!$resultat)
	{
		$_SESSION['state']="1";
		$_SESSION['message']="Bad login or Bad password !";
		$ip = get_ip();
		$req2 = $BDD_ADMINAFK->prepare('INSERT INTO login_fail(login_try, fail, ip, date) VALUES(?, ?, ?, ?)');
		$req2->execute(array($login, "login", $ip, date(' Y-m-d H:i:s')));
		$req2->closeCursor();
	}
	else
	{
		if ($isPasswordCorrect) {
			$ip = get_ip();
			try 
			{
				$req2 = $BDD_ADMINAFK->prepare('UPDATE users SET last_login = ?, last_ip = ? WHERE login = BINARY ?');
				$req2->execute(array(date(' Y-m-d H:i:s'), $ip, $login));
				$req2->closeCursor();
			}
			catch(PDOException $e)
			{
				print "Erreur ! : " . $e->getMessage() . "<br/>";
				die();
			}
			$_SESSION['login'] = $resultat['login'];
			$_SESSION['state']="2";
			$_SESSION['message'] = $login." successfully connected !";
			header('Location: '.$BASE_URL.'admin.php');
			exit();
		}
		$_SESSION['state']="1";
		$_SESSION['message']="Bad login or Bad password !";
		$ip = get_ip();
		$req2 = $BDD_ADMINAFK->prepare('INSERT INTO login_fail(login_try, fail, ip, date) VALUES(?, ?, ?, ?)');
		$req2->execute(array($login, "password", $ip, date(' Y-m-d H:i:s')));
		$req2->closeCursor();
	}
}
else 
{
	$_SESSION['state']="1";
	$_SESSION['message']="All fields must be filled !";
}
	header('Location: '.$BASE_URL.'admin.php');
	exit();

?>