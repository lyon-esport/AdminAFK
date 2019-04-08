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
    $_SESSION['state']="1";
	$_SESSION['message']="You must be logged in to access this page";
	header('Location: '.$BASE_URL.'admin.php');
	exit();
}
$level=3;
if ($result_user['login']==$_SESSION['login'])
{
	$level=2;
	if($result_user['level']==1)
	{
		$level=1;
	}
}
include '../traitement/check_season.php';
$season_display = check_season($BDD_EBOT);
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
			$current = "set_bo3";
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
				<h1 class="text-center">Set Bo3</h1>
				<br>
				<h6 class="text-center">Set 3 matches with 3 differents maps</h6>
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
			}
			if(isset($season_erreur))
			{
				echo "<br>";
				echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>".$season_erreur."<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
			}
			?>
			</div>
			<form method="post" action="../traitement/script_set_bo3.php">
				<div class="container">
					  <div class="form-row">
						<div class="col">
						  <label for="first_id">First ID</label>
						  <input type="number" name="first_id" class="form-control" placeholder="1" min="1" pattern=".{1,}" required title="1 character minimum">
						</div>
						<div class="col">
							<label for="first_map">Map</label>
							<select id="first_map" name="first_map" class="form-control">
								<option selected>de_dust2</option>
								<option>de_cache</option>
								<option>de_inferno</option>
								<option>de_mirage</option>
								<option>de_overpass</option>
								<option>de_cbble</option>
								<option>de_nuke</option>
								<option>de_train</option>
                                <option>de_vertigo</option>
							</select>
						</div>
					  </div>
					  <div class="form-row">
						<div class="col">
						  <label for="second_id">Second ID</label>
						  <input type="number" name="second_id" class="form-control" placeholder="2" min="2" pattern=".{1,}" required title="1 character minimum">
						</div>
						<div class="col">
							<label for="second_map">Map</label>
							<select id="second_map" name="second_map" class="form-control">
								<option selected>de_dust2</option>
								<option>de_cache</option>
								<option>de_inferno</option>
								<option>de_mirage</option>
								<option>de_overpass</option>
								<option>de_cbble</option>
								<option>de_nuke</option>
								<option>de_train</option>
							</select>
						</div>
					  </div>
					  <div class="form-row">
						<div class="col">
						  <label for="third_id">Third ID</label>
						  <input type="number" name="third_id" class="form-control" placeholder="3" min="3" pattern=".{1,}" required title="1 character minimum">
						</div>
						<div class="col">
							<label for="third_map">Map</label>
							<select id="third_map" name="third_map" class="form-control">
								<option selected>de_dust2</option>
								<option>de_cache</option>
								<option>de_inferno</option>
								<option>de_mirage</option>
								<option>de_overpass</option>
								<option>de_cbble</option>
								<option>de_nuke</option>
								<option>de_train</option>
							</select>
						</div>
					  </div>
					  <div class="form-row">
						<div class="col">
						  <label for="rules">Config</label>
						  <input type="text" name="rules" class="form-control" <?php if(isset($CONFIG['default_ebot_rules'])){echo "value='", $CONFIG['default_ebot_rules'],"' ";}?> placeholder="LES" pattern=".{1,}" required title="1 character minimum">
						</div>
						<div class="col">
						  <label for="ebot_pass">Password</label>
						  <input type="text" name="ebot_pass" class="form-control" <?php if(isset($CONFIG['default_ebot_pass'])){echo "value='", $CONFIG['default_ebot_pass'],"' ";}?> placeholder="LES#11">
						</div>
					  </div>
					  <div class="form-row">
							<div class="col">
								<label for="season">Season</label>
								<select id="season" name="season" class="form-control">
									<option></option>
									<?php
									while ($donnees = $season_display->fetch())
									{
										echo "<option>id = ".$donnees['id']." , name = ".$donnees['name']."</option>";
									}
									?>
								</select>
							</div>
							<div class="col">
								<label for="match_mmr">Match MMR</label>
								<select id="match_mmr" name="match_mmr" class="form-control">
									<?php 
									if(isset($CONFIG['default_ebot_match_mmr']))
									{
										$tab_match_mmr = array("15", "12", "9", "5", "3");
										if($CONFIG['default_ebot_match_mmr'] == "15" || $CONFIG['default_ebot_match_mmr'] == "12" || $CONFIG['default_ebot_match_mmr'] == "9" || $CONFIG['default_ebot_match_mmr'] == "5" || $CONFIG['default_ebot_match_mmr'] == "3")
										{
											echo "<option selected>".$CONFIG['default_ebot_match_mmr']."</option>";
											for($i=0; $i<5;$i++)
											{
											if($tab_match_mmr[$i] != $CONFIG['default_ebot_match_mmr']){echo "<option>".$tab_match_mmr[$i]."</option>";}
											}
										}
										else
										{
											echo "<option selected>15</option>";
											echo "<option>12</option>";
											echo "<option>9</option>";
											echo "<option>5</option>";
											echo "<option>3</option>";
										}
									}
									else
									{
										echo "<option selected>15</option>";
										echo "<option>12</option>";
										echo "<option>9</option>";
										echo "<option>5</option>";
										echo "<option>3</option>";
									}
									?>
								</select>
							</div>
						</div>
					  <div class="form-row">
						  <div class="col">
							  <label for="overtime_status">Overtime status</label>
							  <select id="overtime_status" name="overtime_status" class="form-control">
									<?php 
									if(isset($CONFIG['default_ebot_ot_status']))
									{
										if($CONFIG['default_ebot_ot_status'] == "1")
										{
											echo "<option selected>Yes</option>";
											echo "<option>No</option>";
										}
										else
										{
											echo "<option selected>No</option>";
											echo "<option>Yes</option>";
										}
									}
									else
									{
										echo "<option selected>Yes</option>";
										echo "<option>No</option>";
									}
									?>
								</select>
							</div>
							<div class="col">
							  <label for="knife">Knife</label>
							  <select id="knife" name="knife" class="form-control">
									<?php 
									if(isset($CONFIG['default_ebot_knife']))
									{
										if($CONFIG['default_ebot_knife'] == "1")
										{
											echo "<option selected>Yes</option>";
											echo "<option>No</option>";
										}
										else
										{
											echo "<option selected>No</option>";
											echo "<option>Yes</option>";
										}
									}
									else
									{
										echo "<option selected>Yes</option>";
										echo "<option>No</option>";
									}
									?>
								</select>
							</div>
					  </div>
					  <div class="form-row">
						<div class="col">
						  <label for="overtime_mmr">Overtime MMR</label>
						  <select id="overtime_mmr" name="overtime_mmr" class="form-control">
								<?php 
								if(isset($CONFIG['default_ebot_ot_mmr']))
								{
									if($CONFIG['default_ebot_ot_mmr'] == 5)
									{
										echo "<option selected>5</option>";
										echo "<option>3</option>";
									}
									else
									{
										echo "<option selected>3</option>";
										echo "<option>5</option>";
									}
								}
								else
								{
									echo "<option selected>3</option>";
									echo "<option>5</option>";
								}
								?>
							</select>
						</div>
						<div class="col">
						  <label for="overtime_money">Overtime Money</label>
						  <input type="number" name="overtime_money" class="form-control" <?php if(isset($CONFIG['default_ebot_ot_money'])){echo "value='", $CONFIG['default_ebot_ot_money'],"' ";}?> placeholder="16000" min="1" pattern=".{1,}" required title="1 character minimum">
						</div>
					  </div>
					  <br>
					  <?php
					  new_crsf('csrf');
					  echo '<button type="submit" name="update" class="btn text-white bg-secondary btn-lg btn-block">Update</button>';
					  ?>
					  <br>
				</div>
			</form>
			<br><br>
		</div>
		<?php
		$path_img = "../images/";
		display_footer($path_img);
		?>
	</body>
</html>