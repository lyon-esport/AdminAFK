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

include_once 'config/config.php';
include_once 'traitement/check_config.php';
include_once 'traitement/connect_bdd.php';
include_once 'traitement/verif_user.php';
include_once 'traitement/csrf.php';
include_once 'pages/header.php';
include_once 'pages/footer.php';
include_once 'pages/navbar.php';

session_start();
$level=3;
if (isset($_SESSION['login']))
{
	$result_user = check_user($BDD_ADMINAFK, $_SESSION['login']);
	if ($result_user['login']==$_SESSION['login'])
	{
		$level=2;
		$level_char="Admin";
		if($result_user['level']==1)
		{
			$level=1;
			$level_char="Super-Admin";
		}
	}
}
?>
<html>
	<head>
		<?php header_html('', False, $CONFIG['url_glyphicon']); ?>
	</head>
	<body>
		<div class= "page-wrap">
			<?php
			$path_redirect ="pages/";
			$path_redirect_disco ="traitement/";
			$path_redirect_index="";
			$path_img = "images/";
			$current = "admin";
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
				<h1 class="text-center">AdminAFK V1.74 by -MoNsTeRRR</h1>
				<br>
				<h6 class="text-center">Admin access</h6>
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
			?>
			</div>
			<?php
			if (!isset($_SESSION['login']) || ($result_user['login']!=$_SESSION['login']))
			{
				echo '<form method="post" action="traitement/authentication.php">';
				echo '<div class="container">';
				echo '<div class="form-group">';
				echo '<label for="login">Login</label>';
				echo '<input type="text" class="form-control" name="login" id="login" placeholder="Login" pattern=".{4,}" required title="4 characters minimum">';
				echo '</div>';
				echo '<div class="form-group">';
				echo '<label for="pass">Password</label>';
				echo '<input type="password" class="form-control" name="pass" id="pass" placeholder="Password" pattern=".{4,}" required title="4 characters minimum">';
				echo '</div>';
				echo '<br>';
				new_crsf('csrf');
				echo '<button type="submit" name="envoyer" class="btn text-white bg-secondary btn-lg btn-block">Login</button>';
				echo '</div>';
				echo '</form>';
			}
			else
			{
				echo '<div class="d-flex justify-content-center">';
				echo '<h5 class="d-flex align-items-center"><img src="images/other/person.svg" alt="Logo user" width="48" class="rounded float-left">&nbsp&nbsp'.$_SESSION['login'].'</h5>';
				echo '</div>';
				echo '<br>';
				echo '<div class="d-flex justify-content-center">';
				echo '<h5 class="d-flex align-items-center"><img src="images/other/tools.svg" alt="Logo tools" width="48" class="rounded float-left">&nbsp&nbsp'.$level_char.'</h5>';
				echo '</div>';
			}
			?>
			<br><br>
		</div>
		<?php
		$path_img = "images/";
		display_footer($path_img);
		?>
	</body>
</html>