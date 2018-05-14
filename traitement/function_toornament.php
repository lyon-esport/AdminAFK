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

function get_token($client_id, $client_secret, $api_key, $bdd_adminafk)
{
	try 
	{
		$reponse = $bdd_adminafk->query('SELECT number, end_at FROM token WHERE id = 1');
	}
	catch(PDOException $e)
	{
		print "Error ! : " . $e->getMessage() . "<br/>";
		die();
	}
	while ($donnees = $reponse->fetch())
	{
		$access_token = $donnees['number'];
		$end_at	= $donnees['end_at'];
	}
	if(strtotime(date('Y-m-d')) >= strtotime($end_at))
	{
		$curl = curl_init();
		curl_setopt_array(
			$curl, array(
			CURLOPT_URL             => 'https://api.toornament.com/oauth/v2/token?grant_type=client_credentials&client_id='.$client_id.'&client_secret='.$client_secret,
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
		$access_token   = $body->access_token;
		curl_close($curl);
		try 
		{
			$req = $bdd_adminafk->prepare('UPDATE token SET number = ?, created_at = ?, end_at = ? WHERE id = 1');
			$req->execute(array($access_token, date(' Y-m-d'), date('Y-m-d', strtotime("+1 days"))));
			$req->closeCursor();
		}
		catch(PDOException $e)
		{
			print "Error ! : " . $e->getMessage() . "<br/>";
			die();
		}
	}
	return array($access_token, $httpcode);
}

function get_participants($id_toornament, $api_key, $start, $stop)
{
	$curl = curl_init();
	curl_setopt_array(
		$curl, array(
		CURLOPT_URL             => 'https://api.toornament.com/viewer/v2/tournaments/'.$id_toornament.'/participants',
		CURLOPT_RETURNTRANSFER  => true,
		CURLOPT_VERBOSE         => true,
		CURLOPT_HEADER          => true,
		CURLOPT_SSL_VERIFYPEER  => false,
		CURLOPT_HTTPHEADER      => array(
			'X-Api-Key: '.$api_key,
			'Range: participants='.$start.'-'.$stop,
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

function get_stages($id_toornament, $api_key)
{
	$curl = curl_init();
	curl_setopt_array(
		$curl, array(
		CURLOPT_URL             => 'https://api.toornament.com/viewer/v2/tournaments/'.$id_toornament.'/stages',
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

function get_matches($id_toornament, $api_key)
{
	$curl = curl_init();
	curl_setopt_array(
		$curl, array(
		CURLOPT_URL             => 'https://api.toornament.com/viewer/v2/tournaments/'.$id_toornament.'/matches',
		CURLOPT_RETURNTRANSFER  => true,
		CURLOPT_VERBOSE         => true,
		CURLOPT_HEADER          => true,
		CURLOPT_SSL_VERIFYPEER  => false,
		CURLOPT_HTTPHEADER      => array(
			'X-Api-Key: '.$api_key,
			'Range: matches=0-127',
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

function get_streams($id_toornament, $api_key)
{
	$curl = curl_init();
	curl_setopt_array(
		$curl, array(
		CURLOPT_URL             => 'https://api.toornament.com/viewer/v2/tournaments/'.$id_toornament.'/streams',
		CURLOPT_RETURNTRANSFER  => true,
		CURLOPT_VERBOSE         => true,
		CURLOPT_HEADER          => true,
		CURLOPT_SSL_VERIFYPEER  => false,
		CURLOPT_HTTPHEADER      => array(
			'X-Api-Key: '.$api_key,
			'Range: streams=0-49',
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
/*
// 3. Setting a match result
$data = '{
    "status": "completed",
    "opponents": [
        {
            "number": 1,
            "result": 1,
            "score": 16,
            "forfeit": false
        },
        {
            "number": 2,
            "result": 3,
            "score": 12,
            "forfeit": false
        }
    ]
}';
curl_setopt_array(
    $curl, array(
    CURLOPT_URL             => 'https://api.toornament.com/v1/tournaments/{TOURNAMENT_ID}/matches/{MATCH_ID}/result',
    CURLOPT_RETURNTRANSFER  => true,
    CURLOPT_SSL_VERIFYPEER  => false,
    CURLOPT_HTTPHEADER      => array(
        'X-Api-Key: {API_KEY}',
        'Authorization: Bearer '.$access_token,
        'Content-Type: application/json'
    ),
    CURLOPT_CUSTOMREQUEST   => 'PUT',
    CURLOPT_POSTFIELDS      => $data
));
$output         = curl_exec($curl);
$header_size    = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
$header         = substr($output, 0, $header_size);
$body           = json_decode(substr($output, $header_size));
var_dump($body);
*/
// Close request to clear up some resources
//curl_close($curl);

function test_api($client_id, $client_secret, $api_key)
{
	$curl = curl_init();
	curl_setopt_array(
		$curl, array(
		CURLOPT_URL             => 'https://api.toornament.com/oauth/v2/token?grant_type=client_credentials&client_id='.$client_id.'&client_secret='.$client_secret,
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

	return ($httpcode);
}
?>