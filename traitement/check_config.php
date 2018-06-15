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

if(!isset($SERVERNAME_ADMINAFK) || empty($SERVERNAME_ADMINAFK) || !isset($PORT_ADMINAFK) || empty($PORT_ADMINAFK) || !isset($DBNAME_ADMINAFK) || empty($DBNAME_ADMINAFK) || !isset($USERNAME_ADMINAFK) || empty($USERNAME_ADMINAFK) || !isset($PASSWORD_ADMINAFK) || !isset($BASE_URL) || empty($BASE_URL) || !isset($SERVERNAME_EBOT) || empty($SERVERNAME_EBOT) || !isset($PORT_EBOT) || empty($PORT_EBOT) || !isset($DBNAME_EBOT) || empty($DBNAME_EBOT) || !isset($USERNAME_EBOT) || empty($USERNAME_EBOT) || !isset($PASSWORD_EBOT))
{
	echo "/!\ERROR : /!\<br><br>";
	echo "----------------------";
	echo "<br><br>";
	if(!isset($SERVERNAME_ADMINAFK)){echo "\$SERVERNAME_ADMINAFK isn't set <br>";}elseif(empty($SERVERNAME_ADMINAFK)){echo "\$SERVERNAME_ADMINAFK is empty <br>";}
	if(!isset($PORT_ADMINAFK)){echo "\$PORT_ADMINAFK isn't set <br>";}elseif(empty($PORT_ADMINAFK)){echo "\$PORT_ADMINAFK is empty <br>";}
	if(!isset($DBNAME_ADMINAFK)){echo "\$DBNAME_ADMINAFK isn't set <br>";}elseif(empty($DBNAME_ADMINAFK)){echo "\$DBNAME_ADMINAFK is empty <br>";}
	if(!isset($USERNAME_ADMINAFK)){echo "\$USERNAME_ADMINAFK isn't set <br>";}elseif(empty($USERNAME_ADMINAFK)){echo "\$USERNAME_ADMINAFK is empty <br>";}
	if(!isset($PASSWORD_ADMINAFK)){echo "\$PASSWORD_ADMINAFK isn't set <br>";}
	
	if(!isset($BASE_URL)){echo "\$BASE_URL isn't set <br>";}elseif(empty($BASE_URL)){echo "\$BASE_URL is empty <br>";}
	
	if(!isset($SERVERNAME_EBOT)){echo "\$SERVERNAME_EBOT isn't set <br>";}elseif(empty($SERVERNAME_EBOT)){echo "\$SERVERNAME_EBOT is empty <br>";}
	if(!isset($PORT_EBOT)){echo "\$PORT_EBOT isn't set <br>";}elseif(empty($PORT_EBOT)){echo "\$PORT_EBOT is empty <br>";}
	if(!isset($DBNAME_EBOT)){echo "\$DBNAME_EBOT isn't set <br>";}elseif(empty($DBNAME_EBOT)){echo "\$DBNAME_EBOT is empty <br>";}
	if(!isset($USERNAME_EBOT)){echo "\$USERNAME_EBOT isn't set <br>";}elseif(empty($USERNAME_EBOT)){echo "\$USERNAME_EBOT is empty <br>";}
	if(!isset($PASSWORD_EBOT)){echo "\$PASSWORD_EBOT isn't set <br>";}
	die();
}
?>