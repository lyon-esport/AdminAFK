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
if(isset($_SESSION['login']))
{
	$result_user = check_user($BDD_ADMINAFK, $_SESSION['login']);	
	if(!isset($CONFIG['toornament_id']) || empty($CONFIG['toornament_id']) || !isset($CONFIG['toornament_api']) || empty($CONFIG['toornament_api']))
	{
		header('Location: '.$BASE_URL.'pages/status.php');
		exit();
	}
	if(isset($CONFIG['display_stream']) && $CONFIG['display_stream'] == FALSE)
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
		header('Location: '.$BASE_URL.'index.php');
		exit();
	}
	if(isset($CONFIG['display_stream']) && $CONFIG['display_stream'] == FALSE)
	{
		header('Location: '.$BASE_URL.'index.php');
		exit();
	}
}
echo '<html>';
	echo '<head>';
		header_html('../', False, $CONFIG['url_glyphicon']);
	echo '</head>';
	echo '<body>';
		if($embed == false)
		{
			echo '<div class= "page-wrap">';
				$path_redirect ="";
				$path_redirect_disco ="../traitement/";
				$path_redirect_index="../";
				$path_img = "../images/";
				$current = "stream";
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
					echo '<h1 class="text-center">Stream</h1>';
					echo '<br>';
				echo '</div>';
		}
			$result_token = get_token($CONFIG['toornament_client_id'], $CONFIG['toornament_client_secret'], $CONFIG['toornament_api'], $BDD_ADMINAFK, 'organizer:admin');
			$range_start = 0;
			$range_stop = 49;
			if($result_token[1]==200)
			{
				$result_toornament = get_streams($CONFIG['toornament_id'], $CONFIG['toornament_api'], $result_token[0], $range_start, $range_stop);
				if($result_toornament[1]==200 || $result_toornament[1]==206)
				{
					if($result_toornament[1]==206)
					{
						$range_start = $range_start + 50;
						$range_stop = $range_stop + 50;
						$temp_result_toornament[1]=206;
						while($temp_result_toornament[1]==206)
						{
							$temp_result_toornament = get_streams($CONFIG['toornament_id'], $CONFIG['toornament_api'], $result_token[0], $range_start, $range_stop);
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
						for($i=0; $i<count($result_toornament[0]);$i++)
						{
							echo '<div class="container-fluids">';
								echo '<div class="row">';
									echo '<div class="col-1">';
									echo '</div>';
									echo '<div class="col-10">';
										echo '<div class="card">';
											echo '<div class="card-header text-white bg-secondary">'.$result_toornament[0][$i]->name.'</div>';
											echo '<div class="card-body">'; 
											$name_channel = explode("/", $result_toornament[0][$i]->url);
												echo '<div class="row">';
													echo '<div class="embed-responsive embed-responsive-16by9">';
													  echo '<iframe class="embed-responsive-item" src="https://player.twitch.tv/?channel='.$name_channel[3].'&chat=default&autoplay=false" allowfullscreen></iframe>';
													echo '</div>';
												echo '</div>';
											echo '</div>';
										echo '</div>';
									echo '</div>';
								echo '</div>';
							echo '</div>';
							echo '<br>';
						}
					}
					else
					{
						echo '<br>';
						echo '<div class="container">';
						echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>There is no streams<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
						echo '</div>';
					}
				}
				else
				{
					echo '<br>';
					echo '<div class="container">';
					echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>Something wrent wrong, Toornament API code error : ".$result_toornament[1]."<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
					echo '</div>';
				}
			}
			else
			{
				echo '<br>';
				echo '<div class="container">';
				echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>Something wrent wrong, Toornament API code error : ".$result_toornament[1]."<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
				echo '</div>';
			}
			echo '<br>';
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