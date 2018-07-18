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
include_once '../traitement/check_season.php';
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
$season_display = check_season($BDD_EBOT);
$season_display2 = check_season($BDD_EBOT);
?>
<html>
	<head>
		<?php 
		header_html('../', False, $CONFIG['url_glyphicon']); 
		echo '<script type="text/javascript" src="../js/input_file.js"></script>';
		?>
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
			if(!isset($CONFIG['display_veto'])){$CONFIG['display_veto'] = "";}
			if(!isset($CONFIG['display_bracket'])){$CONFIG['display_bracket'] = "";}
			if(!isset($CONFIG['display_participants'])){$CONFIG['display_participants'] = "";}
			if(!isset($CONFIG['display_schedule'])){$CONFIG['display_schedule'] = "";}
			if(!isset($CONFIG['display_stream'])){$CONFIG['display_stream'] = "";}
			display_navbar($current, $path_redirect, $path_redirect_disco, $path_redirect_index, $path_img, $level, $CONFIG['url_ebot'], $CONFIG['toornament_api'], $CONFIG['toornament_client_id'], $CONFIG['toornament_client_secret'], $CONFIG['toornament_id'], $CONFIG['display_connect'], $CONFIG['display_veto'], $CONFIG['display_bracket'], $CONFIG['display_participants'], $CONFIG['display_schedule'], $CONFIG['display_stream']);
			?>
			<div class="container">
				<br>
				<h1 class="text-center">Set tournament</h1>
				<br>
				<h6 class="text-center">Add season, servers, teams faster than with eBot</h6>
				<br>
			</div>
			<div class="container">
			<?php
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
				else if ($_SESSION['state']==3)
				{
					echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>".$_SESSION['message']."<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
					$_SESSION['state']='';
					$_SESSION['message']='';
				}
				echo "<br>";
			}
			?>
			</div>
			<div class="container">
			<?php
			$toornament_message = "";
			if(!isset($CONFIG['toornament_api']) || empty($CONFIG['toornament_api']))
			{
				$toornament_message = "api_key";
			}
			if(!isset($CONFIG['toornament_client_id']) || empty($CONFIG['toornament_client_id']))
			{
				if(!empty($toornament_message))
				{
					$toornament_message = $toornament_message.", client_id";
				}
				else
				{
					$toornament_message = "client_id";
				}
			}
			if(!isset($CONFIG['toornament_client_secret']) || empty($CONFIG['toornament_client_secret']))
			{
				if(!empty($toornament_message))
				{
					$toornament_message = $toornament_message.", client_secret";
				}
				else
				{
					$toornament_message = "client_secret";
				}
			}
			if(!isset($CONFIG['toornament_id']) || empty($CONFIG['toornament_id']))
			{
				if(!empty($toornament_message))
				{
					$toornament_message = $toornament_message.", default_id_toornament";
				}
				else
				{
					$toornament_message = "default_id_toornament";
				}
			}
			if(!empty($toornament_message))
			{
				echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>".$toornament_message." not filled some Toornament features are disable<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
			}
			?>
			</div>
			<div class="container">
				<div class="card">
					<div class="card-header text-white bg-secondary">Create season on eBot</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-bordered">
								<thead class="thead text-center">
									<tr>
										<th scope="col">Name</th>
										<th scope="col">Event</th>
										<th scope="col">Link</th>
										<th scope="col">Action</th>
									</tr>
								</thead>
								<tbody class="text-center">
									<tr>
										<form method='post' action='../traitement/tournament.php'>
											<td><input type="text" name="name" class="form-control" placeholder="Lyon e-Sport" min="1" pattern=".{1,}" required title="1 character minimum"></td>
											<td><input type="text" name="event_name" class="form-control" placeholder="Lyon e-Sport 2018" min="1" pattern=".{1,}" required title="1 character minimum"></td>
											<td><input type="text" name="link_tn" class="form-control" placeholder="https://www.lyon-esport.fr/"></td>
											<?php
											new_crsf("csrf_season_ebot");
											echo "<td><button type='submit' name='choice' value='Create season on eBot' class='btn btn-primary'>Create</button></td>";
											?>
										</form>
									</tr>
								</tbody>
							</table>
						</div>	
					</div>
				</div>
			</div>
			<?php
			if(isset($CONFIG['toornament_api']) && !empty($CONFIG['toornament_api']) && isset($CONFIG['toornament_client_id']) && !empty($CONFIG['toornament_client_id']) && isset($CONFIG['toornament_client_secret']) && !empty($CONFIG['toornament_client_secret']) && isset($CONFIG['toornament_id']) && !empty($CONFIG['toornament_id']))
			{
				echo '<br>';
				echo '<div class="container">';
					echo '<div class="card">';
						echo '<div class="card-header text-white bg-secondary">Create teams on eBot from toornament</div>';
						echo '<div class="card-body">';
							echo '<div class="table-responsive">';
								echo '<table class="table table-bordered">';
									echo '<thead class="thead text-center">';
										echo '<tr>';
											echo '<th scope="col">ID toornament</th>';
											echo '<th scope="col">Season</th>';
											echo '<th scope="col">Action</th>';
										echo '</tr>';
									echo '</thead>';
									echo '<tbody class="text-center">';
										echo '<tr>';
											echo '<form method="post" action="../traitement/tournament.php">';
												echo '<td><fieldset disabled><input type="number" name="id_toornament" class="form-control" value="'.$CONFIG['toornament_id'].'" placeholder="1329897942826893312" min="1" pattern=".{1,}" required title="1 character minimum"></fieldset></td>';
												echo '<td>';
													echo '<select id="season" name="season" class="form-control">';
													echo '<option></option>';
													$k=0;
													while ($donnees = $season_display2->fetch())
													{
														$season_id[$k] = $donnees['id'];
														$season_name[$k] = $donnees['name'];
														echo "<option>id = ".$season_id[$k]." , name = ".$season_name[$k]."</option>";
														$k++;
													}
													echo '</select>';
												echo '</td>';
												new_crsf("csrf_team_toornament");
												echo '<td><button type="submit" name="choice" value="Set Teams on eBot" class="btn btn-primary">Import</button></td>';
											echo '</form>';
										echo '</tr>';
									echo '</tbody>';
								echo '</table>';
							echo '</div>';	
						echo '</div>';
					echo '</div>';
				echo '</div>';
			}
			?>
			<br>
			<div class="container">
				<div class="card">
					<div class="card-header text-white bg-secondary">Create teams on eBot from a file</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-bordered">
								<thead class="thead text-center">
									<tr>
										<th scope="col">Location</th>
										<th scope="col">Season</th>
										<th scope="col">Action</th>
									</tr>
								</thead>
								<tbody class="text-center">
									<tr>
										<form method='post' action='team.php' enctype="multipart/form-data">
											<input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
											<td>
											<div class="custom-file">
												<input type="file" class="custom-file-input" name="file_import_teams" id="file_import_teams" onchange="display_name_file(this, 'label_teams')" required>
												<label class="custom-file-label text-left" id="label_teams" for="file_import_teams"></label>
												</input>
											</div>
											</td>
											<?php
											echo '<td>';
												echo '<select id="season" name="season" class="form-control">';
												echo '<option></option>';
												$k=0;
												while ($donnees = $season_display->fetch())
												{
													$season_id[$k] = $donnees['id'];
													$season_name[$k] = $donnees['name'];
													echo "<option>id = ".$season_id[$k]." , name = ".$season_name[$k]."</option>";
													$k++;
												}
												echo '</select>';
											echo '</td>';
											new_crsf("csrf_team_file");
											echo "<td><button type='submit' name='choice' value='Create teams on eBot' class='btn btn-primary'>Import</button></td>";
											?>
										</form>
									</tr>
								</tbody>
							</table>
						</div>
						<h6>Choose CSV file (Separators : "," / Encoding "UTF-8")</h6>
					</div>
				</div>
			</div>
			<br>
			<div class="container">
				<div class="card">
					<div class="card-header text-white bg-secondary">Create servers on eBot from a file</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-bordered">
								<thead class="thead text-center">
									<tr>
										<th scope="col">Location</th>
										<th scope="col">Action</th>
									</tr>
								</thead>
								<tbody class="text-center">
									<tr>
										<form method='post' action='server.php' enctype="multipart/form-data">
											<input type="hidden" name="MAX_FILE_SIZE" value="1048576"/>
											<td><div class="custom-file">
											<input type="file" class="custom-file-input" name="file_import_servers" id="file_import_servers" onchange="display_name_file(this, 'label_servers')" required>
											<label class="custom-file-label text-left" id="label_servers" for="file_import_servers"></label>
											</div></td>
											<?php
											new_crsf("csrf_server_file");
											echo "<td><button type='submit' name='choice' value='Create servers on eBot' class='btn btn-primary'>Import</button></td>";
											?>
										</form>
									</tr>
								</tbody>
							</table>
						</div>
						<h6>Choose CSV file (Separators : "," / Encoding "UTF-8")</h6>
					</div>
				</div>
			</div>
			<br>
			<div class="container text-center">
				<a href="https://www.toornament.com" target="blank"><img src="../images/other/PoweredbyToor_Black.png" width="120px" class="img-fluid" alt="Powered by Toornament"></a>
			</div>
			<br><br>
		</div>
		<?php
		$path_img = "../images/";
		display_footer($path_img);
		?>
	</body>
</html>