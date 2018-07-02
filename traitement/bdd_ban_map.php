<?php
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
		default:
			exit();
			break;
	}
			
	$req2->execute(array($ban_order, $id));
	$req2->closeCursor();
}
?>