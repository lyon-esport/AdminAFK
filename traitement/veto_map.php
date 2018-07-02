<?php
include_once '../config/config.php';
include_once '../traitement/check_config.php';
include_once '../traitement/connect_bdd.php';
include_once '../traitement/check_input.php';
include_once '../traitement/bdd_ban_map.php';

if(isset($_POST['id']) && !empty($_POST['id']))
{
	$id = $_POST['id'];
	if(verify_input_text('/[^0-9]/', $id))
	{
		exit();
	}
}
else
{
	exit();
}
if(isset($_POST['map']) && !empty($_POST['map']))
{
	$map = $_POST['map'];
	if(verify_input_text("/[\"']/", $map))
	{
		exit();
	}
}
else
{
	exit();
}
update_ban_map($id, $map, $BDD_ADMINAFK);
?>