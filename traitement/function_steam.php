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

function create_steamid_list_toornament($result_toornament, $global_data_steam)
{
	$steam_id_list = '';
	$d = 0;
	$f = 0;
	$e = 0;
	for($p=0;$p<count($result_toornament);$p++)
	{
		if(!empty($result_toornament[$p]->lineup))
		{
			for($q=0;$q<count($result_toornament[$p]->lineup);$q++)
			{
				if(isset($result_toornament[$p]->lineup[$q]->custom_fields->steam_id) && !empty($result_toornament[$p]->lineup[$q]->custom_fields->steam_id))
				{
					$global_data_steam[$f]['steam_toornament'] = $result_toornament[$p]->lineup[$q]->custom_fields->steam_id;
					$steam_split = explode(":", $result_toornament[$p]->lineup[$q]->custom_fields->steam_id);
					$steam_split2 = explode("/", $result_toornament[$p]->lineup[$q]->custom_fields->steam_id);
					if(isset($steam_split[2]))
					{
						$global_data_steam[$f]['steam'] = $steam_split[2];
					}
					elseif(isset($steam_split2[4]))
					{
						$global_data_steam[$f]['steam'] = $steam_split2[4];
					}
					else
					{
						$global_data_steam[$f]['steam'] = $result_toornament[$p]->lineup[$q]->custom_fields->steam_id;
					}
					if(empty($steam_id_list[$e]))
					{
						$steam_id_list[$e] = $result_toornament[$p]->lineup[$q]->custom_fields->steam_id;
					}
					else
					{
						$steam_id_list[$e] = $steam_id_list[$e].','.$result_toornament[$p]->lineup[$q]->custom_fields->steam_id;
					}
					$d++;
					$f++;
					if($d > 49)
					{
						$e++;
						$d = 0;
					}
				}
			}
		}
		else
		{
			if(isset($result_toornament[$p]->custom_fields->steam_id) && !empty($result_toornament[$p]->custom_fields->steam_id))
			{
				$global_data_steam[$f]['steam_toornament'] = $result_toornament[$p]->custom_fields->steam_id;
				$steam_split = explode(":", $result_toornament[$p]->custom_fields->steam_id);
				$steam_split2 = explode("/", $result_toornament[$p]->custom_fields->steam_id);
				if(isset($steam_split[2]))
				{
					$global_data_steam[$f]['steam'] = $steam_split[2];
				}
				elseif(isset($steam_split2[4]))
				{
					$global_data_steam[$f]['steam'] = $steam_split2[4];
				}
				else
				{
					$global_data_steam[$f]['steam'] = $result_toornament[$p]->custom_fields->steam_id;
				}
				if(empty($steam_id_list[$e]))
				{
					$steam_id_list[$e] = $result_toornament[$p]->custom_fields->steam_id;
				}
				else
				{
					$steam_id_list[$e] = $steam_id_list[$e].','.$result_toornament[$p]->custom_fields->steam_id;
				}
				$d++;
				$f++;
				if($d > 49)
				{
					$e++;
					$d = 0;
				}
			}
		}
		$steam_id_list = str_replace(" ", "", $steam_id_list);
		$steam_id_list = str_replace("&", "", $steam_id_list);
	}
	return array($steam_id_list, $global_data_steam);
}

function get_steam_id($api_key, $steam_id_list)
{
	$curl = curl_init();
	curl_setopt_array(
		$curl, array(
		CURLOPT_URL             => 'https://api.steamid.uk/convert.php?api='.$api_key.'&input='.$steam_id_list.'&format=json',
		CURLOPT_RETURNTRANSFER  => true,
		CURLOPT_VERBOSE         => true,
		CURLOPT_HEADER          => true,
		CURLOPT_SSL_VERIFYPEER  => false,
		CURLOPT_HTTPHEADER      => array(
			'X-Api-Key: '.$api_key,
			'Content-Type: application/json'
		)
	));
	$output         = curl_exec($curl);
	$header_size    = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
	$header         = substr($output, 0, $header_size);
	$body           = json_decode(substr($output, $header_size));
	$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	curl_close($curl);

	return array($body, $httpcode);
}

function check_vac_ban($api_key, $steam_id_list)
{
	$curl = curl_init();
	curl_setopt_array(
		$curl, array(
		CURLOPT_URL             => 'http://api.steampowered.com/ISteamUser/GetPlayerBans/v1/?key='.$api_key.'&steamids='.$steam_id_list,
		CURLOPT_RETURNTRANSFER  => true,
		CURLOPT_VERBOSE         => true,
		CURLOPT_HEADER          => true,
		CURLOPT_SSL_VERIFYPEER  => false,
		CURLOPT_HTTPHEADER      => array(
			'X-Api-Key: '.$api_key,
			'Content-Type: application/json'
		)
	));
	$output         = curl_exec($curl);
	$header_size    = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
	$header         = substr($output, 0, $header_size);
	$body           = json_decode(substr($output, $header_size));
	$httpcode 		= curl_getinfo($curl, CURLINFO_HTTP_CODE);
	curl_close($curl);

	return array($body, $httpcode);
}

function recursive_array_search($needle,$haystack) 
{
	foreach($haystack as $key=>$value) {
		$current_key=$key;
		if($needle===$value OR (is_array($value) && recursive_array_search($needle,$value) !== false)) {
			return $current_key;
		}
	}
	return false;
}

function display_vac_ban($result_toornament, $global_data_steam)
{
	$steam_player = "";
	$key_searched = "";
	$key_searched = recursive_array_search($result_toornament->custom_fields->steam_id, $global_data_steam);
	if(isset($global_data_steam[$key_searched]['steam']))
	{
		if(isset($global_data_steam[$key_searched]['NumberOfVACBans']))
		{
			if($global_data_steam[$key_searched]['NumberOfVACBans'] > 0)
			{
				$vacban_number = $global_data_steam[$key_searched]['NumberOfVACBans'];
				$vacban_days = $global_data_steam[$key_searched]['DaysSinceLastBan'];
				$steam_player = '<img class="img-fluid float-right" title="'.$vacban_number.' VAC ban last ban '.$vacban_days.' days ago" style="max-height: 1.7rem; max-width: 1.7rem;" src="../images/other/cancel.svg"/>';	
			}
			else
			{
				$steam_player = '<img class="img-fluid float-right" title="No vac ban recorded" style="max-height: 1.7rem; max-width: 1.7rem;" src="../images/other/exclamation-circle.svg"/>';
			}
		}
		else
		{
			$steam_player = '<img class="img-fluid float-right" title="Error : API or wrong SteamID or SteamID already used by another player" style="max-height: 1.7rem; max-width: 1.7rem;" src="../images/other/high_priority.svg"/>';
		}
	}
	else
	{
		$steam_player = '';
	}
	return $steam_player;
}
?>
