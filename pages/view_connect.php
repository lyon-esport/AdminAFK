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
include_once 'footer.php';
include_once 'navbar.php';
include_once '../traitement/ebot_status.php';

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
	if(isset($CONFIG['display_connect']) && $CONFIG['display_connect'] == FALSE)
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
	if(isset($CONFIG['display_connect']) && $CONFIG['display_connect'] == FALSE)
	{
		header('Location: '.$BASE_URL.'index.php');
		exit();
	}
}
echo '<html>';
	echo '<head>';
		echo '<title>AdminAFK</title>';
		echo '<meta name="description" content="Outil d\'administration avec eBot et toornament par -MoNsTeRRR">';
		echo '<meta name="author" content="Ludovic Ortega">';
		echo '<meta charset="UTF-8">';
		echo '<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">';
		if(isset($CONFIG['url_glyphicon']) && !empty($CONFIG['url_glyphicon']))
		{
			echo '<link rel="icon" type="images/png" href="../'.$CONFIG['url_glyphicon'].'" />';
		}
		echo '<link rel="stylesheet" href="../css/bootstrap.min.css">';
		echo '<link rel="stylesheet" href="../css/dataTables.bootstrap4.min.css">';
		echo '<link rel="stylesheet" href="../css/custom.css">';
		echo '<script src="../js/jquery-3.2.1.slim.min.js"></script>';
		echo '<script src="../js/popper.min.js"></script>';
		echo '<script src="../js/bootstrap.min.js"></script>';
		echo '<script src="../js/jquery.dataTables.min.js"></script>';
		echo '<script src="../js/dataTables.bootstrap4.min.js"></script>';
		echo '<script src="../js/table_script.js"></script>';
	echo '</head>';
	echo '<body>';
	if($embed == false)
	{
		echo '<div class= "page-wrap">';
			$path_redirect ="";
			$path_redirect_disco ="../traitement/";
			$path_redirect_index="../";
			$path_img = "../images/";
			$current = "view_connect";
			if(!isset($CONFIG['url_ebot'])){$CONFIG['url_ebot'] = "";}
			if(!isset($CONFIG['toornament_api'])){$CONFIG['toornament_api'] = "";}
			if(!isset($CONFIG['toornament_client_id'])){$CONFIG['toornament_client_id'] = "";}
			if(!isset($CONFIG['toornament_client_secret'])){$CONFIG['toornament_client_secret'] = "";}
			if(!isset($CONFIG['toornament_id'])){$CONFIG['toornament_id'] = "";}
			if(!isset($CONFIG['display_connect'])){$CONFIG['display_connect'] = "";}
			if(!isset($CONFIG['display_bracket'])){$CONFIG['display_bracket'] = "";}
			if(!isset($CONFIG['display_participants'])){$CONFIG['display_participants'] = "";}
			if(!isset($CONFIG['display_schedule'])){$CONFIG['display_schedule'] = "";}
			if(!isset($CONFIG['display_stream'])){$CONFIG['display_stream'] = "";}
			display_navbar($current, $path_redirect, $path_redirect_disco, $path_redirect_index, $path_img, $level, $CONFIG['url_ebot'], $CONFIG['toornament_api'], $CONFIG['toornament_client_id'], $CONFIG['toornament_client_secret'], $CONFIG['toornament_id'], $CONFIG['display_connect'], $CONFIG['display_bracket'], $CONFIG['display_participants'], $CONFIG['display_schedule'], $CONFIG['display_stream']);
			echo '<div class="container">';
				echo '<br>';
				echo '<h1 class="text-center">Connect team</h1>';
				echo '<br>';
			echo '</div>';
	}
			echo '<div class="container-fluid">';
				echo '<div class="card">';
					echo '<div class="card-header text-white bg-secondary">Matches</div>';
					echo '<div class="card-body">';
						echo '<div class="table-responsive">';
							echo '<table id="connect_teams" class="table table-bordered table-responsive-sm">';
								echo '<thead class="thead text-center">';
									echo '<tr>';
									  echo '<th scope="col">Match id</th>';
									  echo '<th scope="col">Team A</th>';
									  echo '<th scope="col">Team B</th>';
									  echo '<th scope="col">Map</th>';
									  echo '<th scope="col">Server IP</th>';
									  echo '<th scope="col">Connect</th>';
									  echo '<th scope="col">Status</th>';
									echo '</tr>';
								echo '</thead>';
								echo '<tbody class="text-center">';
								$reponse = $BDD_EBOT->query('SELECT matchs.id, matchs.team_a_name, matchs.team_b_name, maps.map_name, matchs.ip, matchs.config_password, matchs.status, matchs.enable FROM matchs LEFT JOIN maps on maps.match_id = matchs.id');
									while ($donnees = $reponse->fetch())
									{
										if(($donnees['status']>1) && ($donnees['status']<13) && ($donnees['enable']>0))
										{
											echo "<tr>";
											  echo "<td class=text-center>", $donnees['id'], "</td>";
											  echo "<td class=text-center>", $donnees['team_a_name'], "</td>";
											  echo "<td class=text-center>", $donnees['team_b_name'], "</td>";
											  echo "<td class=text-center>", $donnees['map_name'], "</td>";
											  if(!empty($donnees['ip']))
											  {
												echo "<td class=text-center>", $donnees['ip'], "</td>";
											  }
											  else
											  {
												echo "<td class=text-center>The IP is not yet available</td>";
											  }
											  if(!empty($donnees['ip']))
											  {
												if(!empty($donnees['config_password']))
												{
													echo "<td class=text-center><a href='steam://connect/", $donnees['ip'], "/",$donnees['config_password'], "'>connect ", $donnees['ip'],"; password ",$donnees['config_password'],"</a></td>";
												}
												else
												{
													echo "<td class=text-center><a href='steam://connect/", $donnees['ip'], "/",$donnees['config_password'], "'>connect ", $donnees['ip'];
												}
											  }
											  else
											  {
												echo "<td class=text-center>The server is not yet available</td>";  
											  }
											  echo "<td class=text-center>", $tab_status[$donnees['status']], "</td>";
											echo "</tr>";
										}
									}
									echo "</tbody>";
									echo "</table>";
								$reponse->closeCursor();
						echo "</div>";
					echo "</div>";
				echo "</div>";
			echo "</div>";
	if($embed == false)
	{
			echo '<br><br>';
		echo '</div>';
		$path_img = "../images/";
		display_footer($path_img);
	}
	echo '</body>';
echo '</html>';
?>