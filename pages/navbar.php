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

function display_navbar($current, $path_redirect, $path_redirect_disco, $path_redirect_index, $path_img, $level, $ip_ebot, $api_key, $client_id, $client_secret, $default_id_toornament, $connect_team_view, $bracket_view, $participants_view, $schedule_view, $stream_view)
{
	$import_match = "";
	$export_match = "";
	$set_bo1 = "";
	$set_bo3 = "";
	$start_map  = "";
	$bracket = "";
	$schedule = "";
	$view_connect = "";
	$participants = "";
	$stream = "";
	$add_admin = "";
	$edit_admin = "";
	$set_tournament = "";
	$setting = "";
	$status = "";
	$log = "";
	$login_attempt = "";
	$disconnect = "";
	$admin_drop = "";
	$info_drop = "";
	switch ($current) 
	{
    case "import_match":
        $import_match = "active";
        break;
    case "export_match":
        $export_match = "active";
        break;
    case "set_bo1":
        $set_bo1 = "active";
        break;
	case "set_bo3":
        $set_bo3 = "active";
        break;
	case "start_map":
        $start_map = "active";
        break;
	case "bracket":
        $bracket = "active";
		$info_drop = "active";
        break;
	case "schedule":
        $schedule = "active";
		$info_drop = "active";
        break;
	case "view_connect":
        $view_connect = "active";
		$info_drop = "active";
        break;
	case "participants":
        $participants = "active";
		$info_drop = "active";
        break;
	case "stream":
        $stream = "active";
		$info_drop = "active";
        break;
	case "add_admin":
        $add_admin = "active";
		$admin_drop = "active";
        break;
	case "edit_admin":
        $edit_admin = "active";
		$admin_drop = "active";
        break;
	case "set_tournament":
        $set_tournament = "active";
		$admin_drop = "active";
        break;
	case "setting":
        $setting = "active";
		$admin_drop = "active";
        break;
	case "status":
        $status = "active";
		$admin_drop = "active";
        break;
	case "log":
        $log = "active";
		$admin_drop = "active";
        break;
	case "login_attempt":
        $login_attempt = "active";
		$admin_drop = "active";
        break;
	case "disconnect":
        $disconnect = "active";
        break;
	}
	echo '<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">';
	  echo '<a class="navbar-brand" href="'.$path_redirect_index.'index.php">AdminAFK</a>';
	  echo '<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">';
		echo '<span class="navbar-toggler-icon"></span>';
	  echo '</button>';
	  echo '<div class="collapse navbar-collapse" id="navbarNav">';
		echo '<ul class="navbar-nav mr-auto">';
		if($level<3)
		{
		  if(isset($api_key) && !empty($api_key) && isset($client_id) && !empty($client_id) && isset($client_secret) && !empty($client_secret) && isset($default_id_toornament) && !empty($default_id_toornament))
		  {
			echo '<li class="nav-item '.$import_match.'">';
			 echo '<a class="nav-link" href="'.$path_redirect.'import_match.php">Import match</a>';
			echo '</li>';
			echo '<li class="nav-item '.$export_match.'">';
			 echo '<a class="nav-link" href="'.$path_redirect.'export_match.php">Export match</a>';
			echo '</li>';
		  }
		  echo '<li class="nav-item '.$set_bo1.'">';
			echo '<a class="nav-link" href="'.$path_redirect.'set_bo1.php">Set Bo1</a>';
		  echo '</li>';
		  echo '<li class="nav-item '.$set_bo3.'">';
			echo '<a class="nav-link" href="'.$path_redirect.'set_bo3.php">Set Bo3</a>';
		  echo '</li>';
		  echo '<li class="nav-item '.$start_map.'">';
			echo '<a class="nav-link" href="'.$path_redirect.'start_map.php">Start match</a>';
		  echo '</li>';
		}
		if($level>2)
		{
			if($connect_team_view)
			{
				echo '<li class="nav-item '.$view_connect.'">';
				 echo '<a class="nav-link" href="'.$path_redirect.'view_connect.php">Connect team</a>';
				echo '</li>';
			}
			if(isset($api_key) && !empty($api_key) && isset($default_id_toornament) && !empty($default_id_toornament))
			{
				if($bracket_view)
				{
					echo '<li class="nav-item '.$bracket.'">';
					 echo '<a class="nav-link" href="'.$path_redirect.'bracket.php">Bracket</a>';
					echo '</li>';
				}
				if($participants_view)
				{	
					echo '<li class="nav-item '.$participants.'">';
					 echo '<a class="nav-link" href="'.$path_redirect.'participants.php">Participants</a>';
					echo '</li>';
				}
				if($schedule_view)
				{	
					echo '<li class="nav-item '.$schedule.'">';
					 echo '<a class="nav-link" href="'.$path_redirect.'schedule.php">Schedule</a>';
					echo '</li>';
				}
				if($stream_view)
				{
					echo '<li class="nav-item '.$stream.'">';
					 echo '<a class="nav-link" href="'.$path_redirect.'stream.php">Stream</a>';
					echo '</li>';
				}
			}
		}
		else
		{
			if(($connect_team_view) || ($bracket_view) || ($participants_view) || ($schedule_view) || ($stream_view))
			{
				echo '<li class="nav-item dropdown">';
				echo '	<a class="nav-link dropdown-toggle '.$info_drop.'" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Infos</a>';
				echo '	<div class="dropdown-menu">';
				if($connect_team_view){echo '  	<a class="dropdown-item '.$view_connect.'" href="'.$path_redirect.'view_connect.php">Connect team</a>';}
				if(isset($api_key) && !empty($api_key) && isset($default_id_toornament) && !empty($default_id_toornament))
				{
					if($bracket_view){echo '<a class="dropdown-item '.$bracket.'" href="'.$path_redirect.'bracket.php">Bracket</a>';}
					if($participants_view){echo '<a class="dropdown-item '.$participants.'" href="'.$path_redirect.'participants.php">Participants</a>';}
					if($schedule_view){echo '<a class="dropdown-item '.$schedule.'" href="'.$path_redirect.'schedule.php">Schedule</a>';}
					if($stream_view){echo '<a class="dropdown-item '.$stream.'" href="'.$path_redirect.'stream.php">Stream</a>';}
				}
				echo '	</div>';
				echo '</li>';
			}
		}
			if($level<2)
			{
				echo '<li class="nav-item dropdown">';
				echo '	<a class="nav-link dropdown-toggle '.$admin_drop.'" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Admin</a>';
				echo '	<div class="dropdown-menu">';
				echo '  	<a class="dropdown-item '.$add_admin.'" href="'.$path_redirect.'add_admin.php">Add admin</a>';
				echo '  	<a class="dropdown-item '.$edit_admin.'" href="'.$path_redirect.'edit_admin.php">Edit admin</a>';
				echo '  	<div class="dropdown-divider"></div>';
				echo '  	<a class="dropdown-item '.$set_tournament.'" href="'.$path_redirect.'set_tournament.php">Set tournament</a>';
				echo '  	<a class="dropdown-item '.$setting.'" href="'.$path_redirect.'setting.php">Setting</a>';
				echo '  	<div class="dropdown-divider"></div>';
				echo '      <a class="dropdown-item '.$status.'" href="'.$path_redirect.'status.php">Status</a>';
				echo '      <a class="dropdown-item '.$log.'" href="'.$path_redirect.'log.php">Log</a>';
				echo '      <a class="dropdown-item '.$login_attempt.'" href="'.$path_redirect.'login_attempt.php">Login fail</a>';
				echo '	</div>';
				echo '</li>';
			}
		if($level<3)
		{
		  echo '<li class="nav-item d-flex align-items-center '.$disconnect.'">';
			echo '<a class="nav-link" href="'.$path_redirect_disco.'disconnect.php">Disconnect</a>';
		  echo '</li>';
		}
		echo '</ul>';
		if(isset($ip_ebot) && !empty($ip_ebot))
		{
			echo '<a class="navbar-brand" href='.$ip_ebot.' target="blank">';
			echo '	<img src="'.$path_img.'other/ebot.png" width="120px" alt="logo ebot">';
			echo '</a>';
		}
	  echo '</div>';
	echo '</nav>';
}
?>