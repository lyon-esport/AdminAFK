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

function update_ban_map($id, $map, $BDD_ADMINAFK)
{	
	$req = $BDD_ADMINAFK->prepare("SELECT * FROM veto WHERE id = ?");
	$req->execute(array($id));
	while ($donnees = $req->fetch())
	{
		$map_status['de_dust2'] = $donnees['de_dust2'];
		$map_status['de_cache'] = $donnees['de_cache'];
		$map_status['de_mirage'] = $donnees['de_mirage'];
		$map_status['de_overpass'] = $donnees['de_overpass'];
		$map_status['de_nuke'] = $donnees['de_nuke'];
		$map_status['de_cobblestone'] = $donnees['de_cobblestone'];
		$map_status['de_train'] = $donnees['de_train'];
		$map_status['de_inferno'] = $donnees['de_inferno'];
		$map_status['de_vertigo'] = $donnees['de_vertigo'];
		$ban_order = $donnees['ban_order'];
	}
	if(!isset($map_status[$map]) || empty($map_status[$map]) || $map_status[$map] == "0" ||!isset($ban_order))
	{
		exit();
	}
	if($ban_order == "0")
	{
		$ban_order = $map;
	}
	else
	{
		$ban_order = $ban_order."/".$map;
	}
	switch($map)
	{
		case "de_dust2":
			$req2 = $BDD_ADMINAFK->prepare("UPDATE veto SET de_dust2 = 0, ban_order = ? WHERE id= ?");
			break;
		case "de_cache":
			$req2 = $BDD_ADMINAFK->prepare("UPDATE veto SET de_cache = 0, ban_order = ? WHERE id= ?");
			break;
		case "de_mirage":
			$req2 = $BDD_ADMINAFK->prepare("UPDATE veto SET de_mirage = 0, ban_order = ? WHERE id= ?");
			break;
		case "de_overpass":
			$req2 = $BDD_ADMINAFK->prepare("UPDATE veto SET de_overpass = 0, ban_order = ? WHERE id= ?");
			break;
		case "de_nuke":
			$req2 = $BDD_ADMINAFK->prepare("UPDATE veto SET de_nuke = 0, ban_order = ? WHERE id= ?");
			break;
		case "de_cobblestone":
			$req2 = $BDD_ADMINAFK->prepare("UPDATE veto SET de_cobblestone = 0, ban_order = ? WHERE id= ?");
			break;
		case "de_train":
			$req2 = $BDD_ADMINAFK->prepare("UPDATE veto SET de_train = 0, ban_order = ? WHERE id= ?");
			break;
		case "de_inferno":
			$req2 = $BDD_ADMINAFK->prepare("UPDATE veto SET de_inferno = 0, ban_order = ? WHERE id= ?");
			break;
		case "de_vertigo":
			$req2 = $BDD_ADMINAFK->prepare("UPDATE veto SET de_vertigo = 0, ban_order = ? WHERE id= ?");
			break;
		default:
			exit();
			break;
	}
			
	$req2->execute(array($ban_order, $id));
	$req2->closeCursor();
}
?>
