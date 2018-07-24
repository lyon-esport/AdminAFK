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
include_once '../traitement/ebot_status.php';

echo '<link rel="stylesheet" href="../css/dataTables.bootstrap4.min.css">';
echo '<script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>';
echo '<script type="text/javascript" src="../js/dataTables.bootstrap4.min.js"></script>';

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
		$reponse = $BDD_EBOT->query('SELECT matchs.id, matchs.team_a_name, matchs.team_b_name, ta.name AS teama_name, tb.name AS teamb_name, maps.map_name, matchs.ip, matchs.config_password, matchs.status, matchs.enable FROM matchs LEFT JOIN maps ON maps.match_id = matchs.id LEFT JOIN teams AS ta ON ta.id = team_a LEFT JOIN teams AS tb ON tb.id = team_b');
		while ($donnees = $reponse->fetch())
		{
		  if(($donnees['status']>1) && ($donnees['status']<13) && ($donnees['enable']>0))
		  {
		    echo "<tr>";
		      echo "<td class=text-center>", $donnees['id'], "</td>";
		      if(isset($donnees['teama_name'])){echo "<td class=text-center>", $donnees['teama_name'], "</td>";}else{echo "<td class=text-center>", $donnees['team_a_name'], "</td>";}
		      if(isset($donnees['teamb_name'])){echo "<td class=text-center>", $donnees['teamb_name'], "</td>";}else{echo "<td class=text-center>", $donnees['team_b_name'], "</td>";}
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
		$reponse->closeCursor();
		echo "</tbody>";
	echo "</table>";
echo "</div>";
?>
