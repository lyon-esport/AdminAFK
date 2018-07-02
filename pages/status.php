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
if((isset($CONFIG['toornament_client_id']))&&(!empty($CONFIG['toornament_client_id']))&&(isset($CONFIG['toornament_client_secret']))&&(!empty($CONFIG['toornament_client_secret']))&&(isset($CONFIG['toornament_api']))&&(!empty($CONFIG['toornament_api'])))
{
	$httpcode_toornament = test_api($CONFIG['toornament_client_id'], $CONFIG['toornament_client_secret'], $CONFIG['toornament_api']);
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
			$current = "status";
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
				<h1 class="text-center">Status</h1>
				<br>
				<h6 class="text-center">Status of general configuration AdminAFK</h6>
				<br>
			</div>
			<div class="container">
			<?php
			echo "<br>";
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
			$pages_display_message = "";
			if(!isset($CONFIG['display_connect']) || $CONFIG['display_connect'] == FALSE)
			{
				$pages_display_message = "Connect team";
			}
			if(!isset($CONFIG['display_veto']) || $CONFIG['display_veto'] == FALSE)
			{
				if(!empty($pages_display_message))
				{
					$pages_display_message = $pages_display_message.", Veto";
				}
				else
				{
					$pages_display_message = "Veto";
				}
			}
			if(isset($CONFIG['toornament_api']) && !empty($CONFIG['toornament_api']) && isset($CONFIG['toornament_id']) && !empty($CONFIG['toornament_id']))
			{
				if(!isset($CONFIG['display_bracket']) || $CONFIG['display_bracket'] == FALSE)
				{
					if(!empty($pages_display_message))
					{
						$pages_display_message = $pages_display_message.", Bracket";
					}
					else
					{
						$pages_display_message = "Bracket";
					}
				}
				if(!isset($CONFIG['display_participants']) || $CONFIG['display_participants'] == FALSE)
				{
					if(!empty($pages_display_message))
					{
						$pages_display_message = $pages_display_message.", Participants";
					}
					else
					{
						$pages_display_message = "Participants";
					}
				}
				if(!isset($CONFIG['display_schedule']) || $CONFIG['display_schedule'] == FALSE)
				{
					if(!empty($pages_display_message))
					{
						$pages_display_message = $pages_display_message.", Schedule";
					}
					else
					{
						$pages_display_message = "Schedule";
					}
				}
				if(!isset($CONFIG['display_stream']) || $CONFIG['display_stream']  == FALSE)
				{
					if(!empty($pages_display_message))
					{
						$pages_display_message = $pages_display_message.", Stream";
					}
					else
					{
						$pages_display_message = "Stream";
					}
				}
			}
			if(!empty($pages_display_message))
			{
				echo "<div class='alert alert-primary alert-dismissible fade show' role='alert'>".$pages_display_message." -> disabled<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
			}
			?>
			</div>
			<div class="container">
				<div class="card">
					<div class="card-header text-white bg-secondary">Other configuration</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-bordered">
								<thead class="thead text-center">
									<tr>
										<th scope="col">Name</th>
										<th scope="col">Set up</th>
									</tr>
								</thead>
								<tbody class="text-center">
									<tr>
										<td class="center">URL eBot</td>
										<td class="center"><?php if(isset($CONFIG['url_ebot'])&&!empty($CONFIG['url_ebot'])){echo '<span class="badge badge-success">'.$CONFIG['url_ebot'].'</span>';}else{echo '<span class="badge badge-warning">Not filled</span>';} ?></td>
									</tr>
									<tr>
										<td class="center">Glyphicon</td>
										<td class="center"><?php if(isset($CONFIG['url_glyphicon'])&&!empty($CONFIG['url_glyphicon'])){echo '<span class="badge badge-success">'.$CONFIG['url_glyphicon'].'</span>';}else{echo '<span class="badge badge-warning">Go in config/other</span>';} ?></td>
									</tr>
								</tbody>
							</table>
						</div>	
					</div>
					
				</div>
			</div>
			<br>
			<div class="container">
				<div class="card">
					<div class="card-header text-white bg-secondary">Default eBot configuration</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-bordered">
								<thead class="thead text-center">
									<tr>
										<th scope="col">Rules</th>
										<th scope="col">Password</th>
										<th scope="col">Match MMR</th>
										<th scope="col">Knife</th>
									</tr>
								</thead>
								<tbody class="text-center">
									<tr>
										<td class="center"><?php if(isset($CONFIG['default_ebot_rules'])&&!empty($CONFIG['default_ebot_rules'])){echo '<span class="badge badge-success">'.$CONFIG['default_ebot_rules'].'</span>';}else{echo '<span class="badge badge-warning">Not filled</span>';} ?></td>
										<td class="center"><?php if(isset($CONFIG['default_ebot_pass'])&&!empty($CONFIG['default_ebot_pass'])){echo '<span class="badge badge-success">'.$CONFIG['default_ebot_pass'].'</span>';}else{echo '<span class="badge badge-warning">Not filled</span>';} ?></td>
										<td class="center"><?php if(isset($CONFIG['default_ebot_match_mmr'])&&!empty($CONFIG['default_ebot_match_mmr'])){echo '<span class="badge badge-success">'.$CONFIG['default_ebot_match_mmr'].'</span>';}else{echo '<span class="badge badge-warning">Not filled</span>';} ?></td>
										<td class="text-center align-middle" rowspan="3"><?php if(isset($CONFIG['default_ebot_ot_money'])&&!empty($CONFIG['default_ebot_ot_money'])){echo '<span class="badge badge-success">'.$CONFIG['default_ebot_ot_money'].'</span>';}else{echo '<span class="badge badge-warning">Not filled</span>';} ?></td>
									</tr>
									<tr>
										<th scope="col">Overtime status</th>
										<th scope="col">Overtime MMR</th>
										<th scope="col">Overtime Money</th>
									</tr>
									<tr>
										<td class="center"><?php if(isset($CONFIG['default_ebot_ot_status'])&&!empty($CONFIG['default_ebot_ot_status'])){echo '<span class="badge badge-success">'.$CONFIG['default_ebot_ot_status'].'</span>';}else{echo '<span class="badge badge-warning">Not filled</span>';} ?></td>
										<td class="center"><?php if(isset($CONFIG['default_ebot_ot_mmr'])&&!empty($CONFIG['default_ebot_ot_mmr'])){echo '<span class="badge badge-success">'.$CONFIG['default_ebot_ot_mmr'].'</span>';}else{echo '<span class="badge badge-warning">Not filled</span>';} ?></td>
										<td class="center"><?php if(isset($CONFIG['default_ebot_ot_money'])&&!empty($CONFIG['default_ebot_ot_money'])){echo '<span class="badge badge-success">'.$CONFIG['default_ebot_ot_money'].'</span>';}else{echo '<span class="badge badge-warning">Not filled</span>';} ?></td>
									</tr>
								</tbody>
							</table>
						</div>	
					</div>
				</div>
			</div>
			<br>
			<div class="container">
				<div class="card">
					<div class="card-header text-white bg-secondary">Toornament configuration</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-bordered">
								<thead class="thead text-center">
									<tr>
										<th scope="col">Client ID</th>
										<th scope="col">Client secret</th>
										<th scope="col">API key</th>
										<th scope="col">Default ID toornament</th>
										<th scope="col">Status</th>
									</tr>
								</thead>
								<tbody class="text-center">
									<tr>
										<td class="center"><?php if(isset($CONFIG['toornament_client_id'])&&!empty($CONFIG['toornament_client_id'])){echo '<span class="badge badge-success">Filled</span>';}else{echo '<span class="badge badge-danger">Not filled</span>';} ?></td>
										<td class="center"><?php if(isset($CONFIG['toornament_client_secret'])&&!empty($CONFIG['toornament_client_secret'])){echo '<span class="badge badge-success">Filled</span>';}else{echo '<span class="badge badge-danger">Not filled</span>';} ?></td>
										<td class="center"><?php if(isset($CONFIG['toornament_api'])&&!empty($CONFIG['toornament_api'])){echo '<span class="badge badge-success">Filled</span>';}else{echo '<span class="badge badge-danger">Not filled</span>';} ?></td>
										<td class="center"><?php if(isset($CONFIG['toornament_id'])&&!empty($CONFIG['toornament_id'])){echo '<span class="badge badge-success">'.$CONFIG['toornament_id'].'</span>';}else{echo '<span class="badge badge-danger">Not filled</span>';} ?></td>
										<td class="center"><?php if(isset($httpcode_toornament)&&($httpcode_toornament==200)){echo '<span class="badge badge-success">Online</span>';}else if(isset($httpcode_toornament)){echo '<span class="badge badge-danger">Error code : '.$httpcode_toornament.'</span>';}else{echo '<span class="badge badge-danger">Error</span>';} ?></td>
									</tr>
								</tbody>
							</table>
						</div>	
					</div>
				</div>
			</div>
			<br>
			<div class="container">
				<div class="card">
					<div class="card-header text-white bg-danger">Status of following pages</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-bordered">
								<thead class="thead text-center">
									<tr>
										<th scope="col">Connect team</th>
										<th scope="col">Veto</th>
										<th scope="col">Bracket</th>
										<th scope="col">Participants</th>
										<th scope="col">Schedule</th>
										<th scope="col">Stream</th>
									</tr>
								</thead>
								<tbody class="text-center">
									<tr>
										<td class="center"><?php if($CONFIG['display_connect']){echo '<span class="badge badge-success">Displayed</span>';}else{echo '<span class="badge badge-danger">Not displayed</span>';} ?></td>
										<td class="center"><?php if($CONFIG['display_veto']){echo '<span class="badge badge-success">Displayed</span>';}else{echo '<span class="badge badge-danger">Not displayed</span>';} ?></td>
										<td class="center"><?php if($CONFIG['display_bracket'] && !empty($CONFIG['toornament_client_id']) && !empty($CONFIG['toornament_client_secret']) && !empty($CONFIG['toornament_api'])){echo '<span class="badge badge-success">Displayed</span>';}else{echo '<span class="badge badge-danger">Not displayed</span>';} ?></td>
										<td class="center"><?php if($CONFIG['display_participants'] && !empty($CONFIG['toornament_client_id']) && !empty($CONFIG['toornament_client_secret']) && !empty($CONFIG['toornament_api'])){echo '<span class="badge badge-success">Displayed</span>';}else{echo '<span class="badge badge-danger">Not displayed</span>';} ?></td>
										<td class="center"><?php if($CONFIG['display_schedule'] && !empty($CONFIG['toornament_client_id']) && !empty($CONFIG['toornament_client_secret']) && !empty($CONFIG['toornament_api'])){echo '<span class="badge badge-success">Displayed</span>';}else{echo '<span class="badge badge-danger">Not displayed</span>';} ?></td>
										<td class="center"><?php if($CONFIG['display_stream'] && !empty($CONFIG['toornament_client_id']) && !empty($CONFIG['toornament_client_secret']) && !empty($CONFIG['toornament_api'])){echo '<span class="badge badge-success">Displayed</span>';}else{echo '<span class="badge badge-danger">Not displayed</span>';} ?></td>
									</tr>
								</tbody>
							</table>
						</div>	
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