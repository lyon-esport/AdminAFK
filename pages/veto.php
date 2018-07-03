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
include_once '../traitement/csrf.php';
include_once 'header.php';
include_once 'navbar.php';
include_once 'footer.php';

session_start();
if (isset($_GET['embed']) && $_GET['embed']=== '1')
{
	$embed = true;
}
else
{
	$embed = false;
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
		echo '<script type="text/javascript" src="../js/veto.js"></script>';
	echo '</head>';
	echo '<body>';
		if($embed == false)
		{
			echo '<div class= "page-wrap">';
				$path_redirect ="";
				$path_redirect_disco ="../traitement/";
				$path_redirect_index="../";
				$path_img = "../images/";
				$current = "veto";
				if(!isset($CONFIG['url_ebot'])){$CONFIG['url_ebot'] = "";}
				if(!isset($CONFIG['toornament_api'])){$CONFIG['toornament_api'] = "";}
				if(!isset($CONFIG['toornament_client_id'])){$CONFIG['toornament_client_id'] = "";}
				if(!isset($CONFIG['toornament_client_secret'])){$CONFIG['toornament_client_secret'] = "";}
				if(!isset($CONFIG['toornament_id'])){$CONFIG['toornament_id'] = "";}
				if(!isset($CONFIG['display_connect'])){$CONFIG['display_connect'] = "";}
				if(!isset($CONFIG['display_veto'])){$CONFIG['display_veto'] = "";}
				if(!isset($CONFIG['display_bracket'])){$CONFIG['display_bracket'] = "";}
				if(!isset($CONFIG['display_participants'])){$CONFIG['display_participants'] = "";}
				if(!isset($CONFIG['display_schedule'])){$CONFIG['display_schedule'] = "";}
				if(!isset($CONFIG['display_stream'])){$CONFIG['display_stream'] = "";}
				display_navbar($current, $path_redirect, $path_redirect_disco, $path_redirect_index, $path_img, $level, $CONFIG['url_ebot'], $CONFIG['toornament_api'], $CONFIG['toornament_client_id'], $CONFIG['toornament_client_secret'], $CONFIG['toornament_id'], $CONFIG['display_connect'], $CONFIG['display_veto'], $CONFIG['display_bracket'], $CONFIG['display_participants'], $CONFIG['display_schedule'], $CONFIG['display_stream']);
				echo '<div class="container">';
					echo '<br>';
					echo '<h1 class="text-center">Veto <span class="badge badge-primary">Béta</span></h1>';
					echo '<br>';
				echo '</div>';
				echo '<div class="container">';
				echo "<br>";
				if(isset($_SESSION['state']) && !empty($_SESSION['state']) && isset($_SESSION['message']) && !empty($_SESSION['message']))
				{
					if($_SESSION['state']==1)
					{
						echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>".$_SESSION['message']."<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
						$_SESSION['state']='';
						$_SESSION['message']='';
					}
					else if ($_SESSION['state']==2)
					{
						echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>".$_SESSION['message']."<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
						$_SESSION['state']='';
						$_SESSION['message']='';
					}
				}
				echo '</div>';
		}
			echo '<form method="post" action="../traitement/create_lobby_veto.php">';	
				echo '<div class="container">';
					echo '<div class="form-row">';
						echo '<div class="col">';
						  echo '<label for="name_1">Name of Team 1</label>';
						  echo '<input type="text" name="name_1" class="form-control" placeholder="Lyon e-Sport" value="Team 1" pattern=".{1,}" required title="1 character minimum">';
						echo '</div>';
						echo '<div class="col">';
						  echo '<label for="name_2">Name of Team 2</label>';
						  echo '<input type="text" name="name_2" class="form-control" placeholder="Lyon e-Sport" value="Team 2" pattern=".{1,}" required title="1 character minimum">';
						echo '</div>';
					echo '</div>';
					echo '<div class="form-row">';
						echo '<div class="col">';
							echo '<label for="format">Format</label>';
							echo '<select id="format" name="format" class="form-control">';
								echo '<option>Best of 1</option>';
								echo '<option>Best of 2</option>';
								echo '<option selected>Best of 3</option>';
								echo '<option>Best of 5</option>';
							echo '</select>';
						echo '</div>';
						echo '<div class="col">';
							echo '<label for="mode">Mode</label>';
							echo '<select id="mode" name="mode" class="form-control">';
								echo '<option>Ban x2, Pick x2, Ban x2 then Random</option>';
								echo '<option>Ban x2, Ban x2, Pick x2 then Random</option>';
								echo '<option>Ban x2, Pick x2 then Random</option>';
								echo '<option>Ban x2, Ban x2 then Random</option>';
								echo '<option>Pick x2 then Random</option>';
								echo '<option>Random</option>';
							echo '</select>';
						echo '</div>';
					echo '</div>';
				echo '</div>';
				echo '<br>';
				echo '<div class="container">';
					echo '<h5>Preview</h5>';
					echo '<ul id="preview" class="list-group">';
					  echo '<li class="list-group-item list-group-item-danger font-weight-bold">Team 1 BAN</li>';
					  echo '<li class="list-group-item list-group-item-danger font-weight-bold">Team 2 BAN</li>';
					  echo '<li class="list-group-item list-group-item-primary font-weight-bold">Team 1 PICK</li>';
					  echo '<li class="list-group-item list-group-item-primary font-weight-bold">Team 2 PICK</li>';
					  echo '<li class="list-group-item list-group-item-danger font-weight-bold">Team 1 BAN</li>';
					  echo '<li class="list-group-item list-group-item-danger font-weight-bold">Team 2 BAN</li>';
					  echo '<li class="list-group-item list-group-item-warning font-weight-bold">RANDOM</li>';
					echo '</ul>';
				echo '</div>';
				echo '<br>';
				echo '<div class="container">';
					echo '<h5>Map list</h5>';
					echo '<div class="col text-center">';
						echo '<img id="de_dust2" style="max-width: 20%;" src="../images/veto/de_dust2.jpg" class="map veto_map rounded" alt="Dust2">';
						echo "<input id='de_dust2_hidden' name='de_dust2' type='hidden' value='yes'>";
						echo '&nbsp';
						echo '<img id="de_cache" style="max-width: 20%;" src="../images/veto/de_cache.jpg" class="map veto_map rounded" alt="Cache">';
						echo "<input id='de_cache_hidden' name='de_cache' type='hidden' value='yes'>";
						echo '&nbsp';
						echo '<img id="de_mirage" style="max-width: 20%;" src="../images/veto/de_mirage.jpg" class="map veto_map rounded" alt="Mirage">';
						echo "<input id='de_mirage_hidden' name='de_mirage' type='hidden' value='yes'>";
						echo '&nbsp';
						echo '<img id="de_inferno" style="max-width: 20%;" src="../images/veto/de_inferno.jpg" class="map veto_map rounded" alt="Inferno">';
						echo "<input id='de_inferno_hidden' name='de_inferno' type='hidden' value='yes'>";
					echo '</div>';
					echo '<br>';
					echo '<div class="col text-center">';
						echo '<img id="de_overpass" style="max-width: 20%;" src="../images/veto/de_overpass.jpg" class="map veto_map rounded" alt="Overpass">';
						echo "<input id='de_overpass_hidden' name='de_overpass' type='hidden' value='yes'>";
						echo '&nbsp';
						echo '<img id="de_cobblestone" style="max-width: 20%;" src="../images/veto/de_cobblestone.jpg" class="map veto_ban rounded" alt="Cobblestone">';
						echo "<input id='de_cobblestone_hidden' name='de_cobblestone' type='hidden' value='no'>";
						echo '&nbsp';
						echo '<img id="de_nuke" style="max-width: 20%;" src="../images/veto/de_nuke.jpg" class="map veto_map rounded" alt="Nuke">';
						echo "<input id='de_nuke_hidden' name='de_nuke' type='hidden' value='yes'>";
						echo '&nbsp';
						echo '<img id="de_train" style="max-width: 20%;" src="../images/veto/de_train.jpg" class="map veto_map rounded" alt="Train">';
						echo "<input id='de_train_hidden' name='de_train' type='hidden' value='yes'>";
					echo '</div>';
					echo '<br>';
				echo '</div>';
				echo '<div class="container">';
					echo '<h5 id="error" class="text-danger text-center"></h5>';
					new_crsf('csrf');
					echo '<button type="submit" id="button_veto" name="veto" class="btn text-white bg-secondary btn-lg btn-block">Create a lobby for map veto</button>';
				echo '</div>';
			echo '</form>';
			echo '<br>';
		if($embed == false)
		{
			echo '</div>';
			$path_img = "../images/";
			display_footer($path_img);
		}
	echo '</body>';
echo '</html>';
?>