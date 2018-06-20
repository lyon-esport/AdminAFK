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
include_once '../traitement/function_toornament.php';
include_once '../traitement/function_steam.php';
include_once 'header.php';
include_once 'footer.php';
include_once 'navbar.php';

session_start();
if (isset($_GET['embed']) && $_GET['embed']=== '1')
{
	$embed = true;
}
else
{
	$embed = false;
}
$level=3;
if(isset($_SESSION['login']))
{
	$result_user = check_user($BDD_ADMINAFK, $_SESSION['login']);
	if(!isset($CONFIG['toornament_id']) || empty($CONFIG['toornament_id']) || !isset($CONFIG['toornament_api']) || empty($CONFIG['toornament_api']))
	{
		header('Location: '.$BASE_URL.'pages/status.php');
		exit();
	}
	if(isset($CONFIG['display_participants']) && $CONFIG['display_participants'] == FALSE)
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
	if(!isset($CONFIG['toornament_id']) || empty($CONFIG['toornament_id']) || !isset($CONFIG['toornament_api']) || empty($CONFIG['toornament_api']))
	{
		header('Location: '.$BASE_URL.'index.php');
		exit();
	}
	if(isset($CONFIG['display_participants']) && $CONFIG['display_participants'] == FALSE)
	{
		header('Location: '.$BASE_URL.'index.php');
		exit();
	}
}
echo '<html>';
	echo '<head>';
		header_html('../', False, $CONFIG['url_glyphicon']);
	echo '</head>';
	echo '<body>';
		if($embed == false)
		{
			echo '<div class= "page-wrap">';
				$path_redirect ="";
				$path_redirect_disco ="../traitement/";
				$path_redirect_index="../";
				$path_img = "../images/";
				$current = "participants";
				if(!isset($CONFIG['url_ebot'])){$CONFIG['url_ebot'] = "";}
				if(!isset($CONFIG['toornament_api'])){$CONFIG['toornament_api'] = "";}
				if(!isset($CONFIG['toornament_client_id'])){$CONFIG['toornament_client_id'] = "";}
				if(!isset($CONFIG['toornament_client_secret'])){$CONFIG['toornament_client_secret'] = "";}
				if(!isset($CONFIG['toornament_id'])){$CONFIG['toornament_id'] = "";}
				if(!isset($CONFIG['display_connect'])){$CONFIG['display_connect'] = "";}
				if(!isset($CONFIG['display_bracket'])){$CONFIG['display_bracket'] = "";}
				if(!isset($CONFIG['display_participants'])){$CONFIG['display_participants'] = "";}
				if(!isset($CONFIG['display_schedule'])){$CONFIG['display_schedule'] = "";}
				if(!isset($CONFIG['display_stream'])){$CONFIG['display_stream'] = "";}
				display_navbar($current, $path_redirect, $path_redirect_disco, $path_redirect_index, $path_img, $level, $CONFIG['url_ebot'], $CONFIG['toornament_api'], $CONFIG['toornament_client_id'], $CONFIG['toornament_client_secret'], $CONFIG['toornament_id'], $CONFIG['display_connect'], $CONFIG['display_bracket'], $CONFIG['display_participants'], $CONFIG['display_schedule'], $CONFIG['display_stream']);
				echo '<div class="container">';
					echo '<br>';
					echo '<h1 class="text-center">Participants</h1>';
					echo '<br>';
				echo '</div>';
		}
			$result_toornament = get_participants($CONFIG['toornament_id'], $CONFIG['toornament_api'], "0", "49");
			if($result_toornament[1]==200 || $result_toornament[1]==206)
			{
				if(count($result_toornament[0])>0)
				{
					/////////////////
					//// API TOORNAMENT PUBLIC TEMP WAITING API V2
					/////////////////
					if(count($result_toornament[0])==50)
					{
						$result2_toornament = get_participants($CONFIG['toornament_id'], $CONFIG['toornament_api'], "50", "99");
						if($result2_toornament[1]==200 || $result2_toornament[1]==206)
						{
							for($p=50;$p<count($result2_toornament[0])+50; $p++)
							{
								$result_toornament[0][$p] = $result2_toornament[0][$p-50];
							}
							if(count($result2_toornament[0])==50)
							{
								$result3_toornament = get_participants($CONFIG['toornament_id'], $CONFIG['toornament_api'], "100", "149");
								if($result3_toornament[1]==200 || $result3_toornament[1]==206)
								{
									for($p=100;$p<count($result3_toornament[0])+100; $p++)
									{
										$result_toornament[0][$p] = $result2_toornament[0][$p-100];
									}
								}
							}
						}
					}
					//////////////////////
					//////Vac BAN Check api steam + api steamid
					//////////////////////
					if(isset($CONFIG['steam_api']) && !empty($CONFIG['steam_api']) && isset($CONFIG['steamid_api']) && !empty($CONFIG['steamid_api']))
					{	
						if(isset($CONFIG['display_vac_ban']) && ($CONFIG['display_vac_ban'] == 1))
						{
							$global_data_steam = array();
							$result_steam_id_list_toornament = create_steamid_list_toornament($result_toornament[0], $global_data_steam);
							$steam_id_list_toornament = $result_steam_id_list_toornament[0];
							$global_data_steam = $result_steam_id_list_toornament[1];
							$steam_id_list_steamid = "";
							for($i = 0; $i < count($steam_id_list_toornament); $i++)
							{
								$steamid_temp = get_steam_id($CONFIG['steamid_api'], $steam_id_list_toornament[$i]);
								if($steamid_temp[1] == 200)
								{
									if(!empty($steamid_temp[0]))
									{
										for($j=0; $j<count($steamid_temp[0]->converted); $j++)
										{
											$key_searched = "";
											$key_searched = recursive_array_search($steamid_temp[0]->converted[$j]->steamid64, $global_data_steam);
											if(empty($key_searched))
											{
												$steam_split = explode(":", $steamid_temp[0]->converted[$j]->steamid);
												if(isset($steam_split[2]))
												{
													$key_searched = recursive_array_search($steam_split[2], $global_data_steam);
												}
											}
											if(empty($key_searched))
											{
												$key_searched = recursive_array_search($steamid_temp[0]->converted[$j]->steam3, $global_data_steam);
											}
											if(!empty($key_searched))
											{
												$global_data_steam[$key_searched]['steamid64'] = $steamid_temp[0]->converted[$j]->steamid64;
												$global_data_steam[$key_searched]['steamid'] = $steamid_temp[0]->converted[$j]->steamid;
												$global_data_steam[$key_searched]['steam3'] = $steamid_temp[0]->converted[$j]->steam3;
											}
										}
										for($j=0; $j<count($steamid_temp[0]->converted);$j++)
										{
											if(empty($steam_id_list_steamid))
											{
												$steam_id_list_steamid = $steamid_temp[0]->converted[$j]->steamid64;
											}
											else
											{
												$steam_id_list_steamid = $steam_id_list_steamid.','.$steamid_temp[0]->converted[$j]->steamid64;
											}
										}
										$result_vac_ban = check_vac_ban($CONFIG['steam_api'], $steam_id_list_steamid);
										if($result_vac_ban[1] == 200)
										{
											for($j=0; $j<count($result_vac_ban[0]->players); $j++)
											{
												$key_searched = "";
												$key_searched = recursive_array_search($result_vac_ban[0]->players[$j]->SteamId, $global_data_steam);
												$global_data_steam[$key_searched]['NumberOfVACBans'] = $result_vac_ban[0]->players[$j]->NumberOfVACBans;
												$global_data_steam[$key_searched]['DaysSinceLastBan'] = $result_vac_ban[0]->players[$j]->DaysSinceLastBan;
											}
										}
									}
								}
								
							}
						}
					}
					///////////////////////
					$size = (int)(count($result_toornament[0])/4);
					$rest = count($result_toornament[0]);
					$off_set = 0;
					echo '<div class="container">';
						for($h=0; $h<$size+1;$h++)
						{
							if($rest>3)
							{
								$max=4;
								echo '<div class="card-deck">';
								for($i=0+$off_set; $i<$max+$off_set;$i++)
								{
									$team_name = $result_toornament[0][$i]->name;
									echo '<div class="card">';
									if(isset($result_toornament[0][$i]->custom_fields->country) && !empty($result_toornament[0][$i]->custom_fields->country))
									{ 
										$flag = '&nbsp<img class="img-fluid rounded float-left" style="max-height: 1.7rem;" src="../images/flags/'.strtolower($result_toornament[0][$i]->custom_fields->country).'.svg"/>';
									}
									else
									{ 
										$flag = '';
									}
										echo '<div class="card-header text-white bg-secondary">'.$flag.'&nbsp'.$team_name.'</div>';
										echo '<ul class="list-group list-group-flush">';
											if(!empty($result_toornament[0][$i]->lineup))
											{	
												$nb_player = count($result_toornament[0][$i]->lineup);
												for($k=0;$k<$nb_player;$k++)
												{
													$name_[$k] = $result_toornament[0][$i]->lineup[$k]->name;
													if(!empty($name_[$k]))
													{
														if(!empty($result_toornament[0][$i]->lineup[$k]->custom_fields->steam_id))
														{
															if(isset($CONFIG['steam_api']) && !empty($CONFIG['steam_api']) && isset($CONFIG['steamid_api']) && !empty($CONFIG['steamid_api']))
															{	
																if(isset($CONFIG['display_vac_ban']) && ($CONFIG['display_vac_ban'] == 1))
																{
																	$steam_player = display_vac_ban($result_toornament[0][$i]->lineup[$k], $global_data_steam);
																}
																else
																{
																	$steam_player = "";
																}
															}
															else
															{
																$steam_player = "";
															}
															$steam_[$k] = $result_toornament[0][$i]->lineup[$k]->custom_fields->steam_id;
															echo '<li class="list-group-item"><a href="https://steamrep.com/search?q='.$steam_[$k].'" target="blank">'.$name_[$k].'</a>'.$steam_player.'</li>';
														}
														else
														{
															echo '<li class="list-group-item">'.$name_[$k].'</a></li>';
														}
													}
													else
													{
														echo '<li class="list-group-item">/!\ Player unknown</a></li>';
													}
												}
											}
											else
											{
												$name = $result_toornament[0][$i]->name;
												if(!empty($result_toornament[0][$i]->custom_fields->steam_id))
												{
													if(isset($CONFIG['steam_api']) && !empty($CONFIG['steam_api']) && isset($CONFIG['steamid_api']) && !empty($CONFIG['steamid_api']))
													{	
														if(isset($CONFIG['display_vac_ban']) && ($CONFIG['display_vac_ban'] == 1))
														{
															$steam_player = display_vac_ban($result_toornament[0][$i], $global_data_steam);
														}
														else
														{
															$steam_player = "";
														}
													}
													else
													{
														$steam_player = "";
													}
													$steam = $result_toornament[0][$i]->custom_fields->steam_id;
													echo '<li class="list-group-item"><a href="https://steamrep.com/search?q='.$steam.'" target="blank">'.$name.'</a>'.$steam_player.'</li>';
												}
												else
												{
													echo '<li class="list-group-item">'.$name.'</a></li>';
												}
											}
										echo '</ul>';
									echo '</div>';
									$rest= $rest-1;
								}
								echo '</div>';
								echo '<br>';
								$off_set = $off_set+4;
							}
							else
							{
								$max = $rest;
								echo '<div class="card-deck">';
								for($i=0+$off_set; $i<$max+$off_set;$i++)
								{
									$team_name = $result_toornament[0][$i]->name;
									echo '<div class="card">';
									if(isset($result_toornament[0][$i]->custom_fields->country) && !empty($result_toornament[0][$i]->custom_fields->country))
									{ 
										$flag = '&nbsp<img class="img-fluid rounded float-left" style="max-height: 1.7rem;" src="../images/flags/'.strtolower($result_toornament[0][$i]->custom_fields->country).'.svg"/>';
									}
									else
									{ 
										$flag = '';
									}
										echo '<div class="card-header text-white bg-secondary">'.$flag.'&nbsp'.$team_name.'</div>';
										echo '<ul class="list-group list-group-flush">';
											if(!empty($result_toornament[0][$i]->lineup))
											{
												$nb_player = count($result_toornament[0][$i]->lineup);
												for($k=0;$k<$nb_player;$k++)
												{
													$name_[$k] = $result_toornament[0][$i]->lineup[$k]->name;
													if(!empty($name_[$k]))
													{
														if(!empty($result_toornament[0][$i]->lineup[$k]->custom_fields->steam_id))
														{
															if(isset($CONFIG['steam_api']) && !empty($CONFIG['steam_api']) && isset($CONFIG['steamid_api']) && !empty($CONFIG['steamid_api']))
															{	
																if(isset($CONFIG['display_vac_ban']) && ($CONFIG['display_vac_ban'] == 1))
																{
																	$steam_player = display_vac_ban($result_toornament[0][$i]->lineup[$k], $global_data_steam);
																}
																else
																{
																	$steam_player = "";
																}
															}
															else
															{
																$steam_player = "";
															}
															$steam_[$k] = $result_toornament[0][$i]->lineup[$k]->custom_fields->steam_id;
															echo '<li class="list-group-item"><a href="https://steamrep.com/search?q='.$steam_[$k].'" target="blank">'.$name_[$k].'</a>'.$steam_player.'</li>';
														}
														else
														{
															echo '<li class="list-group-item">'.$name_[$k].'</a></li>';
														}
													}
													else
													{
														echo '<li class="list-group-item">/!\ Player unknown</a></li>';
													}
												}
											}
											else
											{
												$name = $result_toornament[0][$i]->name;
												if(!empty($result_toornament[0][$i]->custom_fields->steam_id))
												{
													if(isset($CONFIG['steam_api']) && !empty($CONFIG['steam_api']) && isset($CONFIG['steamid_api']) && !empty($CONFIG['steamid_api']))
													{	
														if(isset($CONFIG['display_vac_ban']) && ($CONFIG['display_vac_ban'] == 1))
														{
																$steam_player = display_vac_ban($result_toornament[0][$i], $global_data_steam);
														}
														else
														{
															$steam_player = "";
														}
													}
													else
													{
														$steam_player = "";
													}
													$steam = $result_toornament[0][$i]->custom_fields->steam_id;
													echo '<li class="list-group-item"><a href="https://steamrep.com/search?q='.$steam.'" target="blank">'.$name.'</a>'.$steam_player.'</li>';
												}
												else
												{
													echo '<li class="list-group-item">'.$name.'</a></li>';
												}
											}
										echo '</ul>';
									echo '</div>';
									$rest= $rest-1;
								}
								for($i=0;$i<4-$max;$i++)
								{
									echo '<div class="card border-light">';
									echo '</div>';
								}
								echo '</div>';
								echo '<br>';
							}
						}
					echo '</div>';
					echo '<br>';
				}
				else
				{
					echo '<br>';
					echo '<div class="container">';
					echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>There is no participants<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
					echo '</div>';
				}
			}
			else
			{
				echo '<br>';
				echo '<div class="container">';
				echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>Something wrent wrong, Toornament API code error : ".$result_toornament[1]."<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
				echo '</div>';
			}
			echo '<br>';
			echo '<div class="container text-center">';
				echo '<a href="https://www.toornament.com" target="blank"><img src="../images/other/PoweredbyToor_Black.png" width="120px" class="img-fluid" alt="Powered by Toornament"></a>';
			echo '</div>';
			echo '<br>';
		if($embed == false)
		{
			echo '</div>';
			$path_img = "../images/";
			display_footer($path_img);
		}
	echo '</body>';
echo '</html>';