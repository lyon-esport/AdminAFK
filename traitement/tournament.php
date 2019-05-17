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
include_once 'function_toornament.php';
include_once 'csrf.php';
include_once "check_input.php";
include_once 'check_ip.php';
include_once 'save_log.php';

session_start();
$result_user = check_user($BDD_ADMINAFK, $_SESSION['login']);
if (!isset($_SESSION['login']) || ($result_user['login']!=$_SESSION['login']))
{
    $_SESSION['state']='1';
	$_SESSION['message']="You must be logged in to access this page";
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
if(isset($_POST['choice']))
{	
	$choice=$_POST['choice'];
}
else
{
	$_SESSION['state']="1";
	$_SESSION['message']="Error !";
	header('Location: '.$BASE_URL.'pages/set_tournament.php');
	exit();
}
$ip = get_ip();
if($choice=="Create season on eBot")
{
	if(isset($_POST['name']))
	{	
		$name=$_POST['name'];
		if(verify_input_text("/[\"']/", $name))
		{
			$_SESSION['state']="1";
			$_SESSION['message']="Field \"Name\" does not allow characters : \" '";
			header('Location: '.$BASE_URL.'pages/set_tournament.php');
			exit();
		}
	}
	if(isset($_POST['event_name']))
	{	
		$event_name=$_POST['event_name'];
		if(verify_input_text("/[\"']/", $event_name))
		{
			$_SESSION['state']="1";
			$_SESSION['message']="Field \"Event\" does not allow characters : \" '";
			header('Location: '.$BASE_URL.'pages/set_tournament.php');
			exit();
		}
	}
	if(isset($_POST['link_tn']))
	{	
		$link_tn=$_POST['link_tn'];
		if(verify_input_text("/[\"']/", $link_tn))
		{
			$_SESSION['state']="1";
			$_SESSION['message']="Field \"Link\" does not allow characters : \" '";
			header('Location: '.$BASE_URL.'pages/set_tournament.php');
			exit();
		}
	}
	else
	{
		$link_tn="";
	}
	if(check_csrf("csrf_season_ebot")==false)
	{
		$_SESSION['state']="1";
		$_SESSION['message']="Error CSRF !";
		header('Location: '.$BASE_URL.'pages/set_tournament.php');
		exit();
	}
	$req = $BDD_EBOT->prepare('INSERT INTO seasons(name, event, start, end, link, active, created_at, updated_at) VALUES(?, ?, ?, ?, ?, ?, ?, ?)');
	$req->execute(array($name, $event_name, date(' Y-m-d 00:00:00'), date('Y-m-d 00:00:00', strtotime("+7 days")), $link_tn, "1", date(' Y-m-d H:i:s'), date(' Y-m-d H:i:s')));
	$req->closeCursor();
	$_SESSION['state']="2";
	$_SESSION['message']="Season ".$name." was created !";
	$action=$_SESSION['message'];
	store_action($action, $ip, $BDD_ADMINAFK);
	header('Location: '.$BASE_URL.'pages/set_tournament.php');
	exit();
}

if($choice=="Set Teams on eBot")
{	
	$id_toornament=$CONFIG['toornament_id'];
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
	if(check_csrf("csrf_team_toornament")==false)
	{
		$_SESSION['state']="1";
		$_SESSION['message']="Error CSRF !";
		header('Location: '.$BASE_URL.'pages/set_tournament.php');
		exit();
	}
	$result_token = get_token($CONFIG['toornament_client_id'], $CONFIG['toornament_client_secret'], $CONFIG['toornament_api'], $BDD_ADMINAFK, 'organizer:participant');
	$range_start = 0;
	$range_stop = 49;
	if($result_token[1]==200)
	{
		$result_toornament = get_participants($id_toornament, $CONFIG['toornament_api'], $result_token[0], $range_start, $range_stop);
		if($result_toornament[1]==200 || $result_toornament[1]==206)
		{
			if($result_toornament[1]==206)
			{
				$range_start = $range_start + 50;
				$range_stop = $range_stop + 50;
				$temp_result_toornament[1]=206;
				while($temp_result_toornament[1]==206)
				{
					$temp_result_toornament = get_participants($CONFIG['toornament_id'], $CONFIG['toornament_api'], $result_token[0], $range_start, $range_stop);
					for($p=50;$p<count($temp_result_toornament[0])+50; $p++)
					{
						$result_toornament[0][$p] = $temp_result_toornament[0][$p-50];
					}
					$range_start = $range_start + 50;
					$range_stop = $range_stop + 50;
				}
			}
			if(count($result_toornament[0])>0)
			{
				try 
				{
					for($i = 0; $i <= count($result_toornament[0])-1; $i++)
					{
						if(isset($result_toornament[0][$i]->custom_fields->country)){$flag=$result_toornament[0][$i]->custom_fields->country;}else{$flag="";}
						$req = $BDD_EBOT->prepare('INSERT INTO teams(name, shorthandle, flag, created_at, updated_at) VALUES(?, ?, ?, ?, ?)');
						$req->execute(array($result_toornament[0][$i]->name, $result_toornament[0][$i]->name, $flag, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')));
						if(!empty($season))
						{
							$reponse = $BDD_EBOT->prepare('SELECT id FROM teams WHERE name= ?');
							$reponse->execute(array($result_toornament[0][$i]->name));
							while ($donnees = $reponse->fetch())
							{
								$id_team = $donnees['id'];
							}
							$req2 = $BDD_EBOT->prepare('INSERT INTO teams_in_seasons(season_id, team_id, created_at, updated_at) VALUES(?, ?, ?, ?)');
							$req2->execute(array($season, $id_team, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')));
						}
					}
				}
				catch(PDOException $e)
				{
					print "Erreur ! : " . $e->getMessage() . "<br/>";
					die();
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
				$action=$_SESSION['message'];
				store_action($action, $ip, $BDD_ADMINAFK);
				header('Location: '.$BASE_URL.'pages/set_tournament.php');
				exit();
			}
			else
			{
				$_SESSION['state']="1";
				$_SESSION['message']= "There is no participants";
				header('Location: '.$BASE_URL.'pages/set_tournament.php');
				exit();
			}
		}
		else
		{
			$_SESSION['state']="1";
			$_SESSION['message']= "Something wrent wrong, Toornament API code error : ".$result_toornament[1];
			header('Location: '.$BASE_URL.'pages/set_tournament.php');
			exit();
		}
	}
	else
	{
		$_SESSION['state']="1";
		$_SESSION['message']= "Something wrent wrong, Toornament API code error : ".$result_token[1];
		header('Location: '.$BASE_URL.'pages/set_tournament.php');
		exit();
	}
}
?>