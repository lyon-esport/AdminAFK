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
include_once 'connect_bdd.php';
include_once 'verif_user.php';
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
	header('Location: '.$BASE_URL.'pages/setting.php');
	exit();
}
$ip = get_ip();
if($choice=="other")
{
	if(isset($_POST['ebot']))
	{
		$ebot = $_POST['ebot'];
		if(verify_input_text("/[\"']/", $ebot))
		{
			$_SESSION['state']="1";
			$_SESSION['message']="Field \"eBot URL\" does not allow characters : \" '";
			header('Location: '.$BASE_URL.'pages/setting.php');
			exit();
		}
	}
	else
	{
		$ebot = "";
	}
	if(isset($_POST['logo_glyphicon']))
	{
		$logo_glyphicon = $_POST['logo_glyphicon'];
		if(verify_input_text("/[\"']/", $logo_glyphicon))
		{
			$_SESSION['state']="1";
			$_SESSION['message']="Field \"Path logo (glyphicon)\" does not allow characters : \" '";
			header('Location: '.$BASE_URL.'pages/setting.php');
			exit();
		}
	}
	else
	{
		$logo_glyphicon = "";
	}
	if(check_csrf("csrf_other_configuration")==false)
	{
		$_SESSION['state']="1";
		$_SESSION['message']="Error CSRF !";
		header('Location: '.$BASE_URL.'pages/setting.php');
		exit();
	}
	$req = $BDD_ADMINAFK->prepare('UPDATE configs SET value = ? WHERE name = ?');
	$req->execute(array($ebot, "url_ebot"));
	$req->closeCursor();
	$req->execute(array($logo_glyphicon, "url_glyphicon"));
	$req->closeCursor();
	$_SESSION['state']='2';
	$_SESSION['message']="Updated other configuration !";
	$action=$_SESSION['message'];
	store_action($action, $ip, $BDD_ADMINAFK);
	header('Location: '.$BASE_URL.'pages/setting.php');
	exit();
}

if($choice=="match_ebot")
{	
	if(isset($_POST['rules']))
	{
		$rules = $_POST['rules'];
		if(verify_input_text("/[\"']/", $rules))
		{
			$_SESSION['state']="1";
			$_SESSION['message']="Field \"Rules\" does not allow characters : \" '";
			header('Location: '.$BASE_URL.'pages/setting.php');
			exit();
		}
	}
	else
	{
		$rules = "";
	}
	if(isset($_POST['ebot_pass']))
	{
		$ebot_pass = $_POST['ebot_pass'];
		if(verify_input_text("/[\"']/", $ebot_pass))
		{
			$_SESSION['state']="1";
			$_SESSION['message']="Field \"eBot password\" does not allow characters : \" '";
			header('Location: '.$BASE_URL.'pages/setting.php');
			exit();
		}
	}
	else
	{
		$ebot_pass = "";
	}
	if(isset($_POST['default_ebot_match_mmr']))
	{
		$ebot_match_mmr = $_POST['default_ebot_match_mmr'];
		if(verify_input_text("/[\"']/", $ebot_match_mmr))
		{
			$_SESSION['state']="1";
			$_SESSION['message']="Field \"Match MMR\" does not allow characters : \" '";
			header('Location: '.$BASE_URL.'pages/setting.php');
			exit();
		}
	}
	else
	{
		$ebot_match_mmr = "";
	}
	if(isset($_POST['knife']))
	{
		if($_POST['knife'] != "Yes" && $_POST['knife'] != "No")
		{
			$_SESSION['state']="1";
			$_SESSION['message']="Field \"Knife\" does not allow characters : \" '";
			header('Location: '.$BASE_URL.'pages/setting.php');
			exit();
		}
		else
		{
			if($_POST['knife'] == "Yes")
			{
				$ebot_knife = "1";
			}
			elseif($_POST['knife'] == "No")
			{
				$ebot_knife = "0";
			}
			else
			{
				$ebot_knife = "";
			}
		}
	}
	else
	{
		$ebot_knife = "";
	}
	if(isset($_POST['overtime_status']))
	{
		if($_POST['overtime_status'] != "Yes" && $_POST['overtime_status'] != "No")
		{
			$_SESSION['state']="1";
			$_SESSION['message']="Field \"Overtime status\" does not allow characters : \" '";
			header('Location: '.$BASE_URL.'pages/setting.php');
			exit();
		}
		else
		{
			if($_POST['overtime_status'] == "Yes")
			{
				$overtime_status = "1";
			}
			elseif($_POST['overtime_status'] == "No")
			{
				$overtime_status = "0";
			}
			else
			{
				$overtime_status = "";
			}
		}
	}
	else
	{
		$overtime_status = "";
	}
	if(isset($_POST['overtime_mmr']))
	{
		$overtime_mmr = $_POST['overtime_mmr'];
		if(verify_input_text("/[\"']/", $overtime_mmr))
		{
			$_SESSION['state']="1";
			$_SESSION['message']="Field \"Overtime MMR\" does not allow characters : \" '";
			header('Location: '.$BASE_URL.'pages/setting.php');
			exit();
		}
	}
	else
	{
		$overtime_mmr = "";
	}
	if(isset($_POST['overtime_money']))
	{
		$overtime_money = $_POST['overtime_money'];
		if(verify_input_text("/[\"']/", $overtime_money))
		{
			$_SESSION['state']="1";
			$_SESSION['message']="Field \"Overtime Money\" does not allow characters : \" '";
			header('Location: '.$BASE_URL.'pages/setting.php');
			exit();
		}
	}
	else
	{
		$overtime_money = "";
	}
	if(check_csrf("csrf_default_ebot")==false)
	{
		$_SESSION['state']="1";
		$_SESSION['message']="Error CSRF !";
		header('Location: '.$BASE_URL.'pages/setting.php');
		exit();
	}
	$req = $BDD_ADMINAFK->prepare('UPDATE configs SET value = ? WHERE name = ?');
	$req->execute(array($rules, "default_ebot_rules"));
	$req->closeCursor();
	$req->execute(array($ebot_pass, "default_ebot_pass"));
	$req->closeCursor();
	$req->execute(array($ebot_match_mmr, "default_ebot_match_mmr"));
	$req->closeCursor();
	$req->execute(array($ebot_knife, "default_ebot_knife"));
	$req->closeCursor();
	$req->execute(array($overtime_status, "default_ebot_ot_status"));
	$req->closeCursor();
	$req->execute(array($overtime_mmr, "default_ebot_ot_mmr"));
	$req->closeCursor();
	$req->execute(array($overtime_money, "default_ebot_ot_money"));
	$req->closeCursor();
	$_SESSION['state']='2';
	$_SESSION['message']="Updated default eBot configuration !";
	$action=$_SESSION['message'];
	store_action($action, $ip, $BDD_ADMINAFK);
	header('Location: '.$BASE_URL.'pages/setting.php');
	exit();
}

if($choice=="toornament")
{
	if(isset($_POST['api_key']))
	{
		$api_key = $_POST['api_key'];
		if(verify_input_text("/[\"']/", $api_key))
		{
			$_SESSION['state']="1";
			$_SESSION['message']="Field \"API key\" does not allow characters : \" '";
			header('Location: '.$BASE_URL.'pages/setting.php');
			exit();
		}
	}
	else
	{
		$api_key = "";
	}
	if(isset($_POST['client_id']))
	{
		$client_id = $_POST['client_id'];
		if(verify_input_text("/[\"']/", $client_id))
		{
			$_SESSION['state']="1";
			$_SESSION['message']="Field \"Client ID\" does not allow characters : \" '";
			header('Location: '.$BASE_URL.'pages/setting.php');
			exit();
		}
	}
	else
	{
		$client_id = "";
	}
	if(isset($_POST['client_secret']))
	{
		$client_secret = $_POST['client_secret'];
		if(verify_input_text("/[\"']/", $client_secret))
		{
			$_SESSION['state']="1";
			$_SESSION['message']="Field \"Client secret\" does not allow characters : \" '";
			header('Location: '.$BASE_URL.'pages/setting.php');
			exit();
		}
	}
	else
	{
		$client_secret = "";
	}
	if(isset($_POST['toornament_id']))
	{
		$toornament_id = $_POST['toornament_id'];
		if(verify_input_text("/[\"']/", $toornament_id))
		{
			$_SESSION['state']="1";
			$_SESSION['message']="Field \"Toornament ID\" does not allow characters : \" '";
			header('Location: '.$BASE_URL.'pages/setting.php');
			exit();
		}
	}
	else
	{
		$toornament_id = "";
	}
	if(check_csrf("csrf_toornament")==false)
	{
		$_SESSION['state']="1";
		$_SESSION['message']="Error CSRF !";
		header('Location: '.$BASE_URL.'pages/setting.php');
		exit();
	}
	$req = $BDD_ADMINAFK->prepare('UPDATE configs SET value = ? WHERE name = ?');
	$req->execute(array($api_key, "toornament_api"));
	$req->closeCursor();
	$req->execute(array($client_id, "toornament_client_id"));
	$req->closeCursor();
	$req->execute(array($client_secret, "toornament_client_secret"));
	$req->closeCursor();
	$req->execute(array($toornament_id, "toornament_id"));
	$req->closeCursor();
	$_SESSION['state']='2';
	$_SESSION['message']="Updated toornament configuration !";
	$action=$_SESSION['message'];
	store_action($action, $ip, $BDD_ADMINAFK);
	header('Location: '.$BASE_URL.'pages/setting.php');
	exit();
}

if($choice=="steam_api")
{
	if(isset($_POST['steam_api']))
	{
		$steam_api_key = $_POST['steam_api'];
		if(verify_input_text("/[\"']/", $steam_api_key))
		{
			$_SESSION['state']="1";
			$_SESSION['message']="Field \"API key\" does not allow characters : \" '";
			header('Location: '.$BASE_URL.'pages/setting.php');
			exit();
		}
	}
	else
	{
		$steam_api_key = "";
	}
	if(isset($_POST['steamid_api']))
	{
		$steamid_api_key = $_POST['steamid_api'];
		if(verify_input_text("/[\"']/", $steamid_api_key))
		{
			$_SESSION['state']="1";
			$_SESSION['message']="Field \"API key\" does not allow characters : \" '";
			header('Location: '.$BASE_URL.'pages/setting.php');
			exit();
		}
	}
	else
	{
		$steamid_api_key = "";
	}
	if(isset($_POST['vac_ban']))
	{
		$vac_ban = $_POST['vac_ban'];
	}
	else
	{
		$vac_ban = "0";
	}
	if(check_csrf("csrf_steam_api")==false)
	{
		$_SESSION['state']="1";
		$_SESSION['message']="Error CSRF !";
		header('Location: '.$BASE_URL.'pages/setting.php');
		exit();
	}
	$req = $BDD_ADMINAFK->prepare('UPDATE configs SET value = ? WHERE name = ?');
	$req->execute(array($steam_api_key, "steam_api"));
	$req->closeCursor();
	$req->execute(array($steamid_api_key, "steamid_api"));
	$req->closeCursor();
	$req->execute(array($vac_ban, "display_vac_ban"));
	$req->closeCursor();
	$_SESSION['state']='2';
	$_SESSION['message']="Updated Steam / SteamID configuration !";
	$action=$_SESSION['message'];
	store_action($action, $ip, $BDD_ADMINAFK);
	header('Location: '.$BASE_URL.'pages/setting.php');
	exit();
}

if($choice=="pages")
{
	if(isset($_POST['connect_team_view']))
	{
		$connect_team_view = $_POST['connect_team_view'];
	}
	else
	{
		$connect_team_view = "0";
	}
	if(isset($_POST['veto_view']))
	{
		$veto_view = $_POST['veto_view'];
	}
	else
	{
		$veto_view = "0";
	}
	if(isset($_POST['bracket_view']))
	{
		$bracket_view = $_POST['bracket_view'];
	}
	else
	{
		$bracket_view = "0";
	}
	if(isset($_POST['participants_view']))
	{
		$participants_view = $_POST['participants_view'];
	}
	else
	{
		$participants_view = "0";
	}
	if(isset($_POST['schedule_view']))
	{
		$schedule_view = $_POST['schedule_view'];
	}
	else
	{
		$schedule_view = "0";
	}
	if(isset($_POST['stream_view']))
	{
		$stream_view = $_POST['stream_view'];
	}
	else
	{
		$stream_view = "0";
	}
	if(check_csrf("csrf_pages")==false)
	{
		$_SESSION['state']="1";
		$_SESSION['message']="Error CSRF !";
		header('Location: '.$BASE_URL.'pages/setting.php');
		exit();
	}
	$req = $BDD_ADMINAFK->prepare('UPDATE configs SET value = ? WHERE name = ?');
	$req->execute(array($connect_team_view, "display_connect"));
	$req->closeCursor();
	$req->execute(array($veto_view, "display_veto"));
	$req->closeCursor();
	$req->execute(array($bracket_view, "display_bracket"));
	$req->closeCursor();
	$req->execute(array($participants_view, "display_participants"));
	$req->closeCursor();
	$req->execute(array($schedule_view, "display_schedule"));
	$req->closeCursor();
	$req->execute(array($stream_view, "display_stream"));
	$req->closeCursor();
	$_SESSION['state']='2';
	$_SESSION['message']="Updated activate the following pages !";
	$action=$_SESSION['message'];
	store_action($action, $ip, $BDD_ADMINAFK);
	header('Location: '.$BASE_URL.'pages/setting.php');
	exit();
}
?>
