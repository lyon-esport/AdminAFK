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
include_once '../traitement/function_toornament.php';
include_once 'header.php';
include_once 'footer.php';
include_once 'navbar.php';

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
	if(!isset($CONFIG['toornament_id']) || empty($CONFIG['toornament_id']) || !isset($CONFIG['toornament_api']) || empty($CONFIG['toornament_api']))
	{
		header('Location: '.$BASE_URL.'pages/status.php');
		exit();
	}
	if(isset($CONFIG['display_schedule']) && $CONFIG['display_schedule'] == FALSE)
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
	if(!isset($CONFIG['toornament_id']) || empty($CONFIG['toornament_id']) || !isset($CONFIG['toornament_api']) || empty($CONFIG['toornament_api']))
	{
		header('Location: '.$BASE_URL.'pages/status.php');
		exit();
	}
	if(isset($CONFIG['display_schedule']) && $CONFIG['display_schedule'] == FALSE)
	{
		header('Location: '.$BASE_URL.'index.php');
		exit();
	}
}
echo '<html>';
	echo '<head>';
		header_html('../', True, $CONFIG['url_glyphicon']);
	echo '</head>';
	echo '<body>';
		if($embed == false)
		{
			echo '<div class= "page-wrap">';
				$path_redirect ="";
				$path_redirect_disco ="../traitement/";
				$path_redirect_index="../";
				$path_img = "../images/";
				$current = "schedule";
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
					echo '<h1 class="text-center">Schedule</h1>';
					echo '<br>';
				echo '</div>';
		}
			$result_toornament = get_matches($CONFIG['toornament_id'], $CONFIG['toornament_api']);
			$check_exist_scheduled_datetime = false;

			for($i=0; $i < count($result_toornament[0]); $i++)
			{
				if(!empty($result_toornament[0][$i]->scheduled_datetime) && ($result_toornament[0][$i]->status == "pending" || $result_toornament[0][$i]->status == "running") && (isset($result_toornament[0][$i]->opponents[0]->participant->name) && isset($result_toornament[0][$i]->opponents[1]->participant->name))){$check_exist_scheduled_datetime = true;}
			}
			if($result_toornament[1]==200 || $result_toornament[1]==206)
			{
			  if($check_exist_scheduled_datetime === true)
			  {
				  echo "<div class='container'>";
					echo "<div class='card'>";
						echo "<div class='card-header text-white bg-secondary'>Matches scheduled</div>";
						echo "<div class='card-body'>";
						  echo "<div class='table-responsive'>";
								echo "<table id=connect_teams class='table table-bordered table-responsive-sm'>";
									echo "<thead class='thead text-center'>";
										echo "<tr>";
										  echo "<th scope='col'>Team A</th>";
										  echo "<th scope='col'>Team B</th>";
										  echo "<th scope='col'>Scheduled date (timezone: Europe/Paris)</th>";
										  echo "<th scope='col'>Status</th>";
										echo "</tr>";
									echo "</thead>";
									echo "<tbody class=text-center>";
									for($i=0; $i < count($result_toornament[0]); $i++)
									{
										if(isset($result_toornament[0][$i]->opponents[0]->participant->name) && isset($result_toornament[0][$i]->opponents[1]->participant->name) && isset($result_toornament[0][$i]->scheduled_datetime) && isset($result_toornament[0][$i]->status))
										{
											if($result_toornament[0][$i]->status == "pending" || $result_toornament[0][$i]->status == "running")
											{
												
												$name_team_a = $result_toornament[0][$i]->opponents[0]->participant->name;
												$name_team_b = $result_toornament[0][$i]->opponents[1]->participant->name;
												$dt = new DateTime($result_toornament[0][$i]->scheduled_datetime, new DateTimeZone('UTC'));
												$dt->setTimezone(new DateTimeZone('Europe/Paris'));
												$schedule = date_format($dt, 'l w F Y - H:i:s');
												$status_match = $result_toornament[0][$i]->status;
												echo "<tr>";
													echo "<td class=text-center>".$name_team_a."</td>";
													echo "<td class=text-center>".$name_team_b."</td>";
													echo "<td class=text-center>".$schedule."</td>";
													echo "<td class=text-center>".$status_match."</td>";
												echo "</tr>";
											}
										}
									}
									echo "</tbody>";
								echo "</table>";
						   echo "</div>";
						echo "</div>";
						echo "</div>";
					echo "</div>";
			  }
			  else
			  {
				  echo '<br>';
				  echo '<div class="container">';
				  echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>There is no schedule<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
				  echo '</div>';
			  }
			}
			else
			{
				echo '<br>';
				echo '<div class="container">';
				echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>Something wrent wrong, Toornament API code error : ".$result_toornament[1]."<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
				echo '</div>';
				echo '<br>';
			}
			echo '<div class="container text-center">';
				echo '<a href="https://www.toornament.com" target="blank"><img src="../images/other/PoweredbyToor_Black.png" width="120px" class="img-fluid" alt="Powered by Toornament"></a>';
			echo '</div>';
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