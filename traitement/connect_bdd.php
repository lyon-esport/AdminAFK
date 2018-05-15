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

try
{
    $BDD_ADMINAFK = new PDO("mysql:host=$SERVERNAME_ADMINAFK;port=$PORT_ADMINAFK;dbname=$DBNAME_ADMINAFK;charset=UTF8", $USERNAME_ADMINAFK, $PASSWORD_ADMINAFK);
	$BDD_ADMINAFK->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$global_config = $BDD_ADMINAFK->query('SELECT * FROM configs');
	$CONFIG = array();
	while($resultats_config = $global_config->fetch())
	{
		$CONFIG[$resultats_config['name']] = $resultats_config['value'];
		settype($CONFIG[$resultats_config['name']], $resultats_config['type']);
	}
	$global_config->closeCursor();
} 
catch (PDOException $e) 
{
    print "Erreur ! : " . $e->getMessage() . "<br/>";
    die();
}
try
{
    $BDD_EBOT = new PDO("mysql:host=$SERVERNAME_EBOT;port=$PORT_EBOT;dbname=$DBNAME_EBOT;charset=UTF8", $USERNAME_EBOT, $PASSWORD_EBOT);
	$BDD_EBOT->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} 
catch (PDOException $e) 
{
    print "Error ! : " . $e->getMessage() . "<br/>";
    die();
}
?>