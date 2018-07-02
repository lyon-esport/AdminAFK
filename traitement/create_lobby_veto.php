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

session_start();
$level=3;
if (isset($_SESSION['login']))
{
	$result_user = check_user($BDD_ADMINAFK, $_SESSION['login']);
	if(isset($CONFIG['display_veto']) && $CONFIG['display_veto'] == FALSE)
	{
		header('Location: '.$BASE_URL.'pages/status.php');
		exit();
	}
	if ($result_user['login']==$_SESSION['login'])
	{
		$level=2;
		if($result_user['level']==1)
		{
			$level=1;
		}
	}
}
else
{
	if(isset($CONFIG['display_veto']) && $CONFIG['display_veto'] == FALSE)
	{
		header('Location: '.$BASE_URL.'index.php');
		exit();
	}
}

if(isset($_POST['name_1']) && !empty($_POST['name_1']))
{	
	$name_1=$_POST['name_1'];
	if(verify_input_text("/[\"']/", $name_1))
	{
		$_SESSION['state']="1";
		$_SESSION['message']="Field \"Name of Team 1\" does not allow characters : \" '";
		header('Location: '.$BASE_URL.'pages/veto.php');
		exit();
	}
}
else
{
	$_SESSION['state']="1";
	$_SESSION['message']="Error ! Name of Team 1 is not filled /!\ Operation aborted /!\ ";
	header('Location: '.$BASE_URL.'pages/veto.php');
	exit();
}

if(isset($_POST['name_2']) && !empty($_POST['name_2']))
{	
	$name_2=$_POST['name_2'];
	if(verify_input_text("/[\"']/", $name_2))
	{
		$_SESSION['state']="1";
		$_SESSION['message']="Field \"Name of Team 2\" does not allow characters : \" '";
		header('Location: '.$BASE_URL.'pages/veto.php');
		exit();
	}
}
else
{
	$_SESSION['state']="1";
	$_SESSION['message']="Error ! Name of Team 2 is not filled /!\ Operation aborted /!\ ";
	header('Location: '.$BASE_URL.'pages/veto.php');
	exit();
}

if(isset($_POST['format']) && !empty($_POST['format']))
{	
	$format=$_POST['format'];
}
else
{
	$_SESSION['state']="1";
	$_SESSION['message']="Error ! Format is not filled /!\ Operation aborted /!\ ";
	header('Location: '.$BASE_URL.'pages/veto.php');
	exit();
}

if(isset($_POST['mode']) && !empty($_POST['mode']))
{	
	$mode=$_POST['mode'];
}
else
{
	$_SESSION['state']="1";
	$_SESSION['message']="Error ! Mode is not filled /!\ Operation aborted /!\ ";
	header('Location: '.$BASE_URL.'pages/veto.php');
	exit();
}

if($format == "Best of 1")
{
	$format = "1";
	if($mode == "Ban ... Random")
	{
		$mode = "Ban 1/Ban 2/Ban 1/Ban 2/Ban 1/Ban 2/Random";
	}
	else if($mode == "Ban x2, Ban x2 then Random")
	{
		$mode = "Ban 1/Ban 2/Ban 1/Ban 2/Random";
	}
	else if($mode == "Ban x2 then Random")
	{
		$mode = "Ban 1/Ban 2/Random";
	}
	else if($mode == "Random")
	{
		$mode = "Random";
	}
	else
	{
		$_SESSION['state']="1";
		$_SESSION['message']="You need to choose the Mode of veto !";
		header('Location: '.$BASE_URL.'pages/veto.php');
		exit();
	}
}
else if($format == "Best of 2")
{
	$format = "2";
	if($mode == "Ban x2, Ban x2 then Random")
	{
		$mode = "Ban 1/Ban 2/Ban 1/Ban 2/Random/Random";
	}
	else if($mode == "Ban x2, Ban x2 then Pick x2")
	{
		$mode = "Ban 1/Ban 2/Ban 1/Ban 2/Pick 1/Pick 2";
	}
	else if($mode == "Ban x2 then Random")
	{
		$mode = "Ban 1/Ban 2/Random/Random";
	}
	else if($mode == "Pick x2")
	{
		$mode = "Pick 1/Pick 2";
	}
	else if($mode == "Random")
	{
		$mode = "Random/Random";
	}
	else
	{
		$_SESSION['state']="1";
		$_SESSION['message']="You need to choose the Mode of veto !";
		header('Location: '.$BASE_URL.'pages/veto.php');
		exit();
	}
}
else if($format == "Best of 3")
{
	$format = "3";
	if($mode == "Ban x2, Pick x2, Ban x2 then Random")
	{
		$mode = "Ban 1/Ban 2/Pick 1/Pick 2/Ban 1/Ban 2/Random";
	}
	else if($mode == "Ban x2, Ban x2, Pick x2 then Random")
	{
		$mode = "Ban 1/Ban 2/Ban 1/Ban 2/Pick 1/Pick 2/Random";
	}
	else if($mode == "Ban x2, Pick x2 then Random")
	{
		$mode = "Ban 1/Ban 2/Pick 1/Pick 2/Random";
	}
	else if($mode == "Ban x2, Ban x2 then Random")
	{
		$mode = "Ban 1/Ban 2/Ban 1/Ban 2/Random/Random/Random";
	}
	else if($mode == "Pick x2 then Random")
	{
		$mode = "Pick 1/Pick 2/Random";
	}
	else if($mode == "Random")
	{
		$mode = "Random/Random/Random";
	}
	else
	{
		$_SESSION['state']="1";
		$_SESSION['message']="You need to choose the Mode of veto !";
		header('Location: '.$BASE_URL.'pages/veto.php');
		exit();
	}
}
else if($format == "Best of 5")
{
	$format = "5";
	if($mode == "Ban x2, Pick x2, Pick x2 then Random")
	{
		$mode = "Ban 1/Ban 2/Pick 1/Pick 2/Pick 1/Pick 2/Random";
	}
	else if($mode == "Pick x2, Ban x2, Pick x2 then Random")
	{
		$mode = "Pick 1/Pick 2/Ban 1/Ban 2/Pick 1/Pick 2/Random";
	}
	else if($mode == "Pick x2, Pick x2, Ban x2 then Random")
	{
		$mode = "Pick 1/Pick 2/Pick 1/Pick 2/Ban 1/Ban 2/Random";
	}
	else if($mode == "Pick x2, Pick x2 then Random")
	{
		$mode = "Pick 1/Pick 2/Pick 1/Pick 2/Random";
	}
	else if($mode == "Pick, Ban, Pick, Ban, Pick, Pick then Random")
	{
		$mode = "Pick 1/Ban 2/Pick 1/Ban 2/Pick 1/Pick 2/Random";
	}
	else if($mode == "Random")
	{
		$mode = "Random/Random/Random/Random/Random";
	}
	else
	{
		$_SESSION['state']="1";
		$_SESSION['message']="You need to choose the Mode of veto !";
		header('Location: '.$BASE_URL.'pages/veto.php');
		exit();
	}
}
else
{
	$_SESSION['state']="1";
	$_SESSION['message']="You need to choose the Format of veto !";
	header('Location: '.$BASE_URL.'pages/veto.php');
	exit();
}

$number_map_selected = 0;
if(isset($_POST['de_dust2']) && ($_POST['de_dust2']=="yes" || $_POST['de_dust2']=="no"))
{
	if($_POST['de_dust2'] == "yes")
	{
		$de_dust2 = 1;
		$number_map_selected++;
	}
	else
	{
		$de_dust2 = 0;
	}
}
else
{
	$_SESSION['state']="1";
	$_SESSION['message']="Error during map selection !";
	header('Location: '.$BASE_URL.'pages/veto.php');
	exit();
}

if(isset($_POST['de_cache']) && ($_POST['de_cache']=="yes" || $_POST['de_cache']=="no"))
{
	if($_POST['de_cache'] == "yes")
	{
		$de_cache = 1;
		$number_map_selected++;
	}
	else
	{
		$de_cache = 0;
	}
}
else
{
	$_SESSION['state']="1";
	$_SESSION['message']="Error during map selection !";
	header('Location: '.$BASE_URL.'pages/veto.php');
	exit();
}

if(isset($_POST['de_mirage']) && ($_POST['de_mirage']=="yes" || $_POST['de_mirage']=="no"))
{
	if($_POST['de_mirage'] == "yes")
	{
		$de_mirage = 1;
		$number_map_selected++;
	}
	else
	{
		$de_mirage = 0;
	}
}
else
{
	$_SESSION['state']="1";
	$_SESSION['message']="Error during map selection !";
	header('Location: '.$BASE_URL.'pages/veto.php');
	exit();
}

if(isset($_POST['de_inferno']) && ($_POST['de_inferno']=="yes" || $_POST['de_inferno']=="no"))
{
	if($_POST['de_inferno'] == "yes")
	{
		$de_inferno = 1;
		$number_map_selected++;
	}
	else
	{
		$de_inferno = 0;
	}
}
else
{
	$_SESSION['state']="1";
	$_SESSION['message']="Error during map selection !";
	header('Location: '.$BASE_URL.'pages/veto.php');
	exit();
}

if(isset($_POST['de_overpass']) && ($_POST['de_overpass']=="yes" || $_POST['de_overpass']=="no"))
{
	if($_POST['de_overpass'] == "yes")
	{
		$de_overpass = 1;
		$number_map_selected++;
	}
	else
	{
		$de_overpass = 0;
	}
}
else
{
	$_SESSION['state']="1";
	$_SESSION['message']="Error during map selection !";
	header('Location: '.$BASE_URL.'pages/veto.php');
	exit();
}

if(isset($_POST['de_cobblestone']) && ($_POST['de_cobblestone']=="yes" || $_POST['de_cobblestone']=="no"))
{
	if($_POST['de_cobblestone'] == "yes")
	{
		$de_cobblestone = 1;
		$number_map_selected++;
	}
	else
	{
		$de_cobblestone = 0;
	}
}
else
{
	$_SESSION['state']="1";
	$_SESSION['message']="Error during map selection !";
	header('Location: '.$BASE_URL.'pages/veto.php');
	exit();
}

if(isset($_POST['de_nuke']) && ($_POST['de_nuke']=="yes" || $_POST['de_nuke']=="no"))
{
	if($_POST['de_nuke'] == "yes")
	{
		$de_nuke = 1;
		$number_map_selected++;
	}
	else
	{
		$de_nuke = 0;
	}
}
else
{
	$_SESSION['state']="1";
	$_SESSION['message']="Error during map selection !";
	header('Location: '.$BASE_URL.'pages/veto.php');
	exit();
}

if(isset($_POST['de_train']) && ($_POST['de_train']=="yes" || $_POST['de_train']=="no"))
{
	if($_POST['de_train'] == "yes")
	{
		$de_train = 1;
		$number_map_selected++;
	}
	else
	{
		$de_train = 0;
	}
}
else
{
	$_SESSION['state']="1";
	$_SESSION['message']="Error during map selection !";
	header('Location: '.$BASE_URL.'pages/veto.php');
	exit();
}

if($number_map_selected != 7)
{
	$_SESSION['state']="1";
	$_SESSION['message']="You have to choose at least 7 maps !";
	header('Location: '.$BASE_URL.'pages/veto.php');
	exit();
}

if(check_csrf('csrf')==false)
{
	$_SESSION['state']="1";
	$_SESSION['message']="Error CSRF !";
	header('Location: '.$BASE_URL.'pages/veto.php');
	exit();
}

try
{
	$req = $BDD_ADMINAFK->prepare('INSERT INTO veto (date, team_1, team_2, format, mode, de_dust2, de_cache, de_mirage, de_overpass, de_nuke, de_cobblestone, de_train, de_inferno) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
	$req->execute(array(date('Y-m-d H:i:s'), $name_1, $name_2, $format, $mode, $de_dust2, $de_cache, $de_mirage, $de_overpass, $de_nuke, $de_cobblestone, $de_train, $de_inferno));
	$req->closeCursor();
}
catch (PDOException $e) 
{
	print "Error ! : " . $e->getMessage() . "<br/>";
	die();
}
	header('Location: '.$BASE_URL.'pages/lobby_veto.php?id='.$BDD_ADMINAFK->lastInsertId());
	exit();

?>