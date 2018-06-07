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
include_once 'footer.php';
include_once 'navbar.php';

session_start();
$result_user = check_user($BDD_ADMINAFK, $_SESSION['login']);
if (!isset($_SESSION['login']) || ($result_user['login']!=$_SESSION['login']))
{
    $_SESSION['state']='1';
	$_SESSION['message']="You must be logged in to access this page";
	header('Location: '.$BASE_URL.'admin.php');
	exit();
}
$level=3;
if ($result_user['login']==$_SESSION['login'])
{
	if($result_user['level']>1)
	{
		$level=2;
		$_SESSION['state']='1';
		$_SESSION['message']="You must be Super-Admin to have access to this";
		header('Location: '.$BASE_URL.'admin.php');
		exit();
	}
	$level=1;
}
?>
<html>
	<head>
		<?php header_html('../', False, $CONFIG['url_glyphicon']); ?>
	</head>
	<body>
		<div class= "page-wrap">
			<?php
			$path_redirect ="";
			$path_redirect_disco ="../traitement/";
			$path_redirect_index="../";
			$path_img = "../images/";
			$current = "set_tournament";
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
			?>
			<div class="container">
				<br>
				<h1 class="text-center">Set teams</h1>
				<br>
			</div>
			<?php
			if(isset($_POST['season']))
			{
				$season=$_POST['season'];
			}
			else 
			{
				$season="";
			}
			$extensions_valides = array( 'csv' );
			if(isset($_POST['MAX_FILE_SIZE']))
			{
				$MAX_FILE_SIZE = $_POST['MAX_FILE_SIZE'];
			}
			else
			{
				$_SESSION['state']='1';
				$_SESSION['message']="Error !";
				header('Location: '.$BASE_URL.'pages/set_tournament.php');
				exit();
			}
			if(check_csrf("csrf_team_file")==false)
			{
				$_SESSION['state']="1";
				$_SESSION['message']="Error CSRF !";
				header('Location: '.$BASE_URL.'pages/set_tournament.php');
				exit();
			}
			if ($_FILES['file_import_teams']['error'] > 0)
			{
				$_SESSION['state']='1';
				$_SESSION['message']="Error while uploading !";
				header('Location: '.$BASE_URL.'pages/set_tournament.php');
				exit();
			}
			else
			{
				if($_FILES['file_import_teams']['size'] <= $MAX_FILE_SIZE)
				{
					$extension_upload = strtolower(substr(strrchr($_FILES['file_import_teams']['name'], '.'),1));
					if(in_array($extension_upload,$extensions_valides))
					{
						if (($handle = fopen($_FILES['file_import_teams']['tmp_name'], "r")) !== FALSE) 
						{
							echo '<div class="container">';
								echo '<div class="card">';
									echo '<div class="card-header text-white bg-secondary">Teams list</div>';
									echo '<div class="card-body">';
										echo '<div class="table-responsive">';
											echo '<form method="post" action="../traitement/add_team.php">';
												echo '<table class="table table-bordered">';
													echo '<thead class="thead text-center">';
														echo '<tr>';
															echo '<th scope="col">Name</th>';
															echo '<th scope="col">Shorthandle</th>';
															echo '<th scope="col">Flag</th>';
															echo '<th scope="col">Link</th>';
														echo '</tr>';
													echo '</thead>';
													echo '<tbody class="text-center">';
							$k = 0;
							while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
							{
								echo '<tr>';
								echo '<td><input type="text" name="name_team'.$k.'" class="form-control" value="'.$data[0].'" placeholder="Name team" pattern=".{4,}" required title="Name team"></td>';
								echo '<td><input type="text" name="shortname_team'.$k.'" class="form-control" value="'.$data[1].'" placeholder="Shortname team" pattern=".{4,}" required title="Shortname team"></td>';
								echo '<td><input type="text" name="flag_team'.$k.'" class="form-control" value="'.$data[2].'" placeholder="Flag Team (ISO)"></td>';
								echo '<td><input type="text" name="link_team'.$k.'" class="form-control" value="'.$data[3].'" placeholder="Website"></td>';
								echo '</tr>';
								$k++;
							}
														echo '<input id="number_team" name="number_team" type="hidden" value="'.$k.'">';
													echo '</tbody>';
												echo '</table>';
												echo '<input id="season" name="season" type="hidden" value="'.$season.'">';
												new_crsf("csrf");
												echo '<button type="submit" name="choice" value="Import team" class="btn btn-secondary btn-block">Confirm import</button>';
											echo '</form>';
										echo '</div>';
									echo '</div>';
								echo '</div>';
								echo '</div>';
							fclose($handle);
						}
						else
						{
							$_SESSION['state']='1';
							$_SESSION['message']="Error the file can't be opened !";
							header('Location: '.$BASE_URL.'pages/set_tournament.php');
							exit();
						}

					}
					else
					{
						$_SESSION['state']='1';
						$_SESSION['message']="Wrong extension !";
						header('Location: '.$BASE_URL.'pages/set_tournament.php');
						exit();
					}
				}
				else
				{
					$_SESSION['state']='1';
					$_SESSION['message']="File to big !";
					header('Location: '.$BASE_URL.'pages/set_tournament.php');
					exit();
				}
			}
			?>
			<br><br>
		</div>
		<?php
		$path_img = "../images/";
		display_footer($path_img);
		?>
	</body>
</html>