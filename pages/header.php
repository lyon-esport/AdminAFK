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

function header_html($before, $table, $url_glyphicon)
{
	echo '<title>AdminAFK</title>';
	echo '<meta name="description" content="Outil d\'administration avec eBot et toornament par -MoNsTeRRR">';
	echo '<meta name="author" content="Ludovic Ortega">';
	echo '<meta charset="UTF-8">';
	echo '<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">';
	if(isset($url_glyphicon) && !empty($url_glyphicon))
	{
		echo '<link rel="icon" type="images/png" href="'.$url_glyphicon.'" />';
	}
	echo '<link rel="stylesheet" href="'.$before.'css/bootstrap.min.css">';
	echo '<link rel="stylesheet" href="'.$before.'css/custom.css">';
	echo '<script src="'.$before.'js/jquery-3.2.1.slim.min.js"></script>';
	echo '<script src="'.$before.'js/popper.min.js"></script>';
	echo '<script src="'.$before.'js/bootstrap.min.js"></script>';
	if($table == True)
	{
		echo '<link rel="stylesheet" href="../css/dataTables.bootstrap4.min.css">';
		echo '<script src="'.$before.'js/jquery.dataTables.min.js"></script>';
		echo '<script src="'.$before.'js/dataTables.bootstrap4.min.js"></script>';
		echo '<script src="'.$before.'js/table_script.js"></script>';
	}
}
?>