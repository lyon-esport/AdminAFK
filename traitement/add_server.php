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
include_once 'check_ip.php';
include_once 'save_log.php';

session_start();
$result_user = check_user($BDD_ADMINAFK, $_SESSION['login']);
if (!isset($_SESSION['login']) || ($result_user['login']!=$_SESSION['login']))
{
    $_SESSION['state']="1";
	$_SESSION['message']="You must be logged in to access this page";
	header('Location: '.$BASE_URL.'index.php');
	exit();
}
if(isset($_POST['number_server']))
{	
	$number_server=$_POST['number_server'];
}
else
{
	$_SESSION['state']="1";
	$_SESSION['message']="Error !";
	header('Location: '.$BASE_URL.'pages/set_tournament.php');
	exit();
}
if(check_csrf("csrf")==false)
{
	$_SESSION['state']="1";
	$_SESSION['message']="Error CSRF !";
	header('Location: '.$BASE_URL.'pages/set_tournament.php');
	exit();
}
for($i=0; $i<$number_server; $i++)
{
	if(isset($_POST['ip_server'.$i]))
	{	
		$ip_server[$i]=$_POST['ip_server'.$i];
	}
	if(isset($_POST['name_server'.$i]))
	{	
		$name_server[$i]=$_POST['name_server'.$i];
	}
	if(isset($_POST['password_server'.$i]))
	{	
		$password_server[$i]=$_POST['password_server'.$i];
	}
	if(isset($_POST['gotv_server'.$i]))
	{	
		$gotv_server[$i]=$_POST['gotv_server'.$i];
	}
	else
	{
		$gotv_server[$i]="";
	}
}
$req1 = $BDD_EBOT->prepare('INSERT INTO servers(ip, rcon, hostname, tv_ip, created_at, updated_at) VALUES(?, ?, ?, ?, ?, ?)');
for($i=0; $i<$number_server; $i++)
{
	$req1->execute(array($ip_server[$i], $password_server[$i], $name_server[$i] ,$gotv_server[$i], date(' Y-m-d H:i:s'), date(' Y-m-d H:i:s')));
	$req1->closeCursor();
}

$_SESSION['state']="2";
$_SESSION['message']=$number_server." servers have been added on eBot";

$ip = get_ip();
$action=$_SESSION['message'];
store_action($action, $ip, $BDD_ADMINAFK);

header('Location: '.$BASE_URL.'pages/set_tournament.php');
exit();

?>