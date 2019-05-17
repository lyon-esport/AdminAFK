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
if(isset($_POST['number_team']))
{	
	$number_team=$_POST['number_team'];
}
else
{
	$_SESSION['state']="1";
	$_SESSION['message']="Error !";
	header('Location: '.$BASE_URL.'pages/set_tournament.php');
	exit();
}
if(isset($_POST['season']) && !empty($_POST['season']))
{	
	$chaine=explode(" ", $_POST['season']);
	$season=$chaine[2];
	$season_name=$chaine[6];
}
else 
{
	$season="";
}
if(check_csrf("csrf")==false)
{
	$_SESSION['state']="1";
	$_SESSION['message']="Error CSRF !";
	header('Location: '.$BASE_URL.'pages/set_tournament.php');
	exit();
}
for($i=0; $i<$number_team; $i++)
{
	if(isset($_POST['name_team'.$i]))
	{	
		$name_team[$i]=$_POST['name_team'.$i];
	}
	if(isset($_POST['shortname_team'.$i]))
	{	
		$shortname_team[$i]=$_POST['shortname_team'.$i];
	}
	if(isset($_POST['flag_team'.$i]))
	{	
		$flag_team[$i]=$_POST['flag_team'.$i];
	}
	else
	{
		$flag_team[$i]="";
	}
	if(isset($_POST['link_team'.$i]))
	{	
		$link_team[$i]=$_POST['link_team'.$i];
	}
	else
	{
		$link_team[$i]="";
	}
}

$req1 = $BDD_EBOT->prepare('INSERT INTO teams(name, shorthandle, flag, link, created_at, updated_at) VALUES(?, ?, ?, ?, ?, ?)');
$reponse = $BDD_EBOT->prepare('SELECT id FROM teams WHERE name= ?');
$req2 = $BDD_EBOT->prepare('INSERT INTO teams_in_seasons(season_id, team_id, created_at, updated_at) VALUES(?, ?, ?, ?)');
for($i=0; $i<$number_team; $i++)
{
	$req1->execute(array($name_team[$i], $shortname_team[$i], $flag_team[$i] ,$link_team[$i], date(' Y-m-d H:i:s'), date(' Y-m-d H:i:s')));
	$req1->closeCursor();
	if(!empty($season))
	{
		$reponse->execute(array($name_team[$i]));
		while ($donnees = $reponse->fetch())
		{
			$id_team = $donnees['id'];
		}
		$req2->execute(array($season, $id_team, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')));
	}
}

$_SESSION['state']="2";
if(empty($season))
{	
	$_SESSION['message']=($i)."  teams have been added on eBot from toornament";
}
else
{
	$_SESSION['message']=($i)." teams have been added on eBot from toornament in ".$season_name." season";
}
$ip = get_ip();
$action=$_SESSION['message'];
store_action($action, $ip, $BDD_ADMINAFK);

header('Location: '.$BASE_URL.'pages/set_tournament.php');
exit();

?>