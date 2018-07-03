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
include_once '../traitement/check_config.php';
include_once '../traitement/connect_bdd.php';
include_once '../traitement/verif_user.php';
include_once '../traitement/check_input.php';
include_once '../traitement/bdd_ban_map.php';
include_once 'header.php';

if(isset($_GET['id']) && !empty($_GET['id']))
{
	$id = $_GET['id'];
	if(verify_input_text('/[^0-9]/', $id))
	{
		$_SESSION['state']="1";
		$_SESSION['message']="The lobby id must be a number !";
		header('Location: '.$BASE_URL.'pages/veto.php');
		exit();
	}
}
else
{
	$_SESSION['state']='1';
	$_SESSION['message']="The lobby doesn't exist !";
	header('Location: '.$BASE_URL.'pages/veto.php');
	exit();
}
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
echo '<html>';
	echo '<head>';
		header_html('../', False, $CONFIG['url_glyphicon']);
		echo '<script type="text/javascript" src="../js/lobby_veto.js"></script>';
	echo '</head>';
	echo '<body>';
		$reponse = $BDD_ADMINAFK->prepare('SELECT * FROM veto WHERE id = ?');
		$reponse->execute(array($id));
		$count_result = $reponse->rowCount();
		if($count_result == "0")
		{
			$_SESSION['state']='1';
			$_SESSION['message']="There is an error in the lobby, you need to create a new room";
			header('Location: '.$BASE_URL.'pages/veto.php');
			exit();
		}
		$error_get_bdd = 0;
		while ($donnees = $reponse->fetch())
		{
			if(isset($donnees['team_1'])){$team_1 = $donnees['team_1'];}else{$error_get_bdd = 1;}
			if(isset($donnees['team_2'])){$team_2 = $donnees['team_2'];}else{$error_get_bdd = 1;}
			if(isset($donnees['format'])){$format = $donnees['format'];}else{$error_get_bdd = 1;}
			if(isset($donnees['mode'])){$mode = $donnees['mode'];}else{$error_get_bdd = 1;}
			if(isset($donnees['de_dust2'])){$de_dust2 = $donnees['de_dust2'];}else{$error_get_bdd = 1;}
			if(isset($donnees['de_cache'])){$de_cache = $donnees['de_cache'];}else{$error_get_bdd = 1;}
			if(isset($donnees['de_mirage'])){$de_mirage = $donnees['de_mirage'];}else{$error_get_bdd = 1;}
			if(isset($donnees['de_overpass'])){$de_overpass = $donnees['de_overpass'];}else{$error_get_bdd = 1;}
			if(isset($donnees['de_nuke'])){$de_nuke = $donnees['de_nuke'];}else{$error_get_bdd = 1;}
			if(isset($donnees['de_cobblestone'])){$de_cobblestone = $donnees['de_cobblestone'];}else{$error_get_bdd = 1;}
			if(isset($donnees['de_train'])){$de_train = $donnees['de_train'];}else{$error_get_bdd = 1;}
			if(isset($donnees['de_inferno'])){$de_inferno = $donnees['de_inferno'];}else{$error_get_bdd = 1;}
			if(isset($donnees['ban_order'])){$ban_order = $donnees['ban_order'];}else{$error_get_bdd = 1;}
		}
		$reponse->closeCursor();
		if($error_get_bdd == 1)
		{
			$_SESSION['state']='1';
			$_SESSION['message']="There is an error in the lobby, you need to create a new room";
			header('Location: '.$BASE_URL.'pages/veto.php');
			exit();
		}
		$map = array(
			"de_dust2" => $de_dust2,
			"de_cache" => $de_cache,
			"de_mirage" => $de_mirage,
			"de_overpass" => $de_overpass,
			"de_nuke" => $de_nuke,
			"de_cobblestone" => $de_cobblestone,
			"de_train" => $de_train,
			"de_inferno" => $de_inferno
		);
		$veto_order = array();
		if($ban_order != "0")
		{
			$split_veto_order = explode("/", $ban_order);
			for($i=0;$i<count($split_veto_order);$i++)
			{
				$veto_order[$i] = $split_veto_order[$i];
			}
		}
		$veto_mode = array();
		$split_mode = explode("/", $mode);
		for($i=0; $i<count($split_mode);$i++)
		{
			$split_split_mode = explode(" ", $split_mode[$i]);
			if($split_split_mode[0] != "Random")
			{
				$veto_mode[$i]['action'] = $split_split_mode[0];
				if($split_split_mode[1] == "1")
				{
					$veto_mode[$i]['team'] = $team_1;
				}
				else
				{
					$veto_mode[$i]['team'] = $team_2;
				}
			}
			else
			{
				$veto_mode[$i]['action'] = $split_split_mode[0];
			}
		}
		echo '<div class="container-fluids">';
			echo '<div class="row">';
				echo '<div class="col">';
					echo '<h4 class="text-center">'.$team_1.'</h4>';
				echo '</div>';
				echo '<div class="col-6">';
					echo '<br><br><br>';
					///////////////////
					/////MODAL/////////
					///////////////////
					echo '<div class="modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">';
					  echo '<div class="modal-dialog modal-lg modal-dialog-centered" role="document">';
						echo '<div class="modal-content">';
						  echo '<div class="modal-header">';
							echo '<h5 class="modal-title" id="exampleModalLabel">Choose a map to ban</h5>';
							echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
							  echo '<span aria-hidden="true">&times;</span>';
							echo '</button>';
						  echo '</div>';
						  echo '<div class="modal-body">';
							  echo '<div class="form-group">';
								echo '<div class="col text-center">';
									if($map['de_dust2']==1){echo '<img id="de_dust2" style="max-width: 30%;" src="../images/veto/de_dust2.jpg" class="map veto_map rounded" alt="Dust2">&nbsp';}
									if($map['de_cache']==1){echo '<img id="de_cache" style="max-width: 30%;" src="../images/veto/de_cache.jpg" class="map veto_map rounded" alt="Cache">&nbsp';}
									if($map['de_mirage']==1){echo '<img id="de_mirage" style="max-width: 30%;" src="../images/veto/de_mirage.jpg" class="map veto_map rounded" alt="Mirage">&nbsp';}
									if($map['de_inferno']==1){echo '<img id="de_inferno" style="max-width: 30%;" src="../images/veto/de_inferno.jpg" class="map veto_map rounded" alt="Inferno">&nbsp';}
								echo '</div>';
								echo '<br>';
								echo '<div class="col text-center">';
									if($map['de_overpass']==1){echo '<img id="de_overpass" style="max-width: 30%;" src="../images/veto/de_overpass.jpg" class="map veto_map rounded" alt="Overpass">';}
									if($map['de_cobblestone']==1){echo '<img id="de_cobblestone" style="max-width: 30%;" src="../images/veto/de_cobblestone.jpg" class="map veto_map rounded" alt="Cobblestone">&nbsp';}
									if($map['de_nuke']==1){echo '<img id="de_nuke" style="max-width: 30%;" src="../images/veto/de_nuke.jpg" class="map veto_map rounded" alt="Nuke">&nbsp';}
									if($map['de_train']==1){echo '<img id="de_train" style="max-width: 30%;" src="../images/veto/de_train.jpg" class="map veto_map rounded" alt="Train">';}
								echo '</div>';
							  echo '</div>';
						  echo '</div>';
						  echo '<div class="modal-footer">';
							echo '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';
						  echo '</div>';
						echo '</div>';
					  echo '</div>';
					echo '</div>';
					////////////////////////////
					echo '<ul id="preview" class="list-group">';
					  $map_ban = "";
					  $next_veto = "";
					  $random_ban = "";
					  $map_ban = "";
					  $k = 0;
					  for($i=0;$i<count($veto_mode);$i++)
					  {
						  if(isset($veto_order[$i]) && !empty($veto_order[$i]))
						  {
							  $map_ban = '<img style="max-width: auto; height: 3rem;" src="../images/veto/'.$veto_order[$i].'.jpg" class="rounded float-right" alt="'.$veto_order[$i].'">';
						  }
						  else if($k == 0)
						  {
							  $next_veto = '<button type="button" class="btn btn-light float-right" data-toggle="modal" data-target="#exampleModal" data-whatever="map">Choose map</button>'; 
							  if($veto_mode[$i]['action'] != "Random")
							  {
								$k++;
							  }
						  }
						  else
						  {
							  $map_ban = '';
						  }
						  if($veto_mode[$i]['action'] == "Ban")
						  {
							  echo '<li class="list-group-item list-group-item-danger font-weight-bold">'.$veto_mode[$i]['team'].' BAN'.$map_ban.$next_veto.'</li>';
						  }
						  else if($veto_mode[$i]['action'] == "Pick")
						  {
							  echo '<li class="list-group-item list-group-item-primary font-weight-bold">'.$veto_mode[$i]['team'].' PICK'.$map_ban.$next_veto.'</li>';
						  }
						  else if($veto_mode[$i]['action'] == "Random")
						  {
							  if($k == 0 && !isset($veto_order[$i]))
							  {
								  $map_random = array();
								  $e = 0;
								  foreach ($map as $key => $value) 
								  {
									  if($value == "1")
									  {
										  $map_random[$e] = $key;
										  $e++;
									  }
								  }
								  $random_ban = $map_random[rand(0, count($map_random)-1)];
								  update_ban_map($id, $random_ban, $BDD_ADMINAFK);
								  $map_ban = '<img style="max-width: auto; height: 3rem;" src="../images/veto/'.$random_ban.'.jpg" class="rounded float-right" alt="'.$random_ban.'">';

							  }
							  echo '<li class="list-group-item list-group-item-warning font-weight-bold">RANDOM'.$map_ban.'</li>';
						  }
						  $map_ban = "";
						  $next_veto = "";
						  $random_ban = "";
						  $map_ban = "";
					  }
					echo '</ul>';
				echo '</div>';
				echo '<div class="col">';
					echo '<h4 class="text-center">'.$team_2.'</h4>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
	echo '</body>';
echo '</html>';
?>