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

$(document).ready(function() {
	$( "#format" ).change(function() {
		var format = $( "#format" ).val();
		if(format == "Best of 1")
		{
			var add_mode = ['Ban ... Random', 'Ban x2, Ban x2 then Random', 'Ban x2 then Random', 'Random'];
			var add_preview = [
				['Team 1 BAN', 'Team 2 BAN', 'Team 1 BAN', 'Team 2 BAN', 'Team 1 BAN', 'Team 2 BAN', 'RANDOM'],
				['list-group-item-danger', 'list-group-item-danger', 'list-group-item-danger', 'list-group-item-danger', 'list-group-item-danger', 'list-group-item-danger', 'list-group-item-warning']
			];
		}
		else if(format == "Best of 2")
		{
			var add_mode = ['Ban x2, Ban x2 then Random', 'Ban x2, Ban x2 then Pick x2', 'Ban x2 then Random', 'Pick x2', 'Random'];
			var add_preview = [
				['Team 1 BAN', 'Team 2 BAN', 'Team 1 BAN', 'Team 2 BAN', 'RANDOM', 'RANDOM'],
				['list-group-item-danger', 'list-group-item-danger', 'list-group-item-danger', 'list-group-item-danger', 'list-group-item-warning', 'list-group-item-warning']
			];
		}
		else if(format == "Best of 3")
		{
			var add_mode = ['Ban x2, Pick x2, Ban x2 then Random', 'Ban x2, Ban x2, Pick x2 then Random', 'Ban x2, Pick x2 then Random', 'Ban x2, Ban x2 then Random', 'Pick x2 then Random', 'Random'];
			var add_preview = [
				['Team 1 BAN', 'Team 2 BAN', 'Team 1 PICK', 'Team 2 PICK', 'Team 1 BAN', 'Team 2 BAN', 'RANDOM'],
				['list-group-item-danger', 'list-group-item-danger', 'list-group-item-primary', 'list-group-item-primary', 'list-group-item-danger', 'list-group-item-danger', 'list-group-item-warning']
			];
		}
		else if(format == "Best of 5")
		{
			var add_mode = ['Ban x2, Pick x2, Pick x2 then Random', 'Pick x2, Ban x2, Pick x2 then Random','Pick x2, Pick x2, Ban x2 then Random','Pick x2, Pick x2 then Random','Pick, Ban, Pick, Ban, Pick, Pick then Random','Random'];
			var add_preview = [
				['Team 1 BAN', 'Team 2 BAN', 'Team 1 PICK', 'Team 2 PICK', 'Team 1 PICK', 'Team 2 PICK', 'RANDOM'],
				['list-group-item-danger', 'list-group-item-danger', 'list-group-item-primary', 'list-group-item-primary', 'list-group-item-primary', 'list-group-item-primary', 'list-group-item-warning']
			];
		}
		else
		{
			location.reload(true);
		}
	    $("#mode").empty();
		for(var i=0; i<add_mode.length; i++){
			$('#mode')
				.append($("<option></option>")
				.text(add_mode[i]));
		}
		$("#preview").empty();
		for(var i=0; i<add_preview[0].length; i++){
			$('#preview')
				.append($("<li class='list-group-item font-weight-bold'></li>")
				.addClass(add_preview[1][i])
				.text(add_preview[0][i]));
		}
	});
	$( "#mode" ).change(function() {
		var format = $( "#format" ).val();
		var mode = $( "#mode" ).val();
		
		if(mode == "Ban ... Random")
		{
			var add_preview = [
				['Team 1 BAN', 'Team 2 BAN', 'Team 1 BAN', 'Team 2 BAN', 'Team 1 BAN', 'Team 2 BAN', 'RANDOM'],
				['list-group-item-danger', 'list-group-item-danger', 'list-group-item-danger', 'list-group-item-danger', 'list-group-item-danger', 'list-group-item-danger', 'list-group-item-warning']
			];
		}
		else if(mode == "Ban x2, Pick x2, Pick x2 then Random")
		{
			var add_preview = [
				['Team 1 BAN', 'Team 2 BAN', 'Team 1 PICK', 'Team 2 PICK', 'Team 1 PICK', 'Team 2 PICK', 'RANDOM'],
				['list-group-item-danger', 'list-group-item-danger', 'list-group-item-primary', 'list-group-item-primary', 'list-group-item-primary', 'list-group-item-primary', 'list-group-item-warning']
			];
		}
		else if(mode == "Pick x2, Ban x2, Pick x2 then Random")
		{
			var add_preview = [
				['Team 1 PICK', 'Team 2 PICK', 'Team 1 BAN', 'Team 2 BAN', 'Team 1 PICK', 'Team 2 PICK', 'RANDOM'],
				['list-group-item-primary', 'list-group-item-primary', 'list-group-item-danger', 'list-group-item-danger', 'list-group-item-primary', 'list-group-item-primary', 'list-group-item-warning']
			];
		}
		else if(mode == "Pick x2, Pick x2, Ban x2 then Random")
		{
			var add_preview = [
				['Team 1 PICK', 'Team 2 PICK', 'Team 1 PICK', 'Team 2 PICK', 'Team 1 BAN', 'Team 2 BAN', 'RANDOM'],
				['list-group-item-primary', 'list-group-item-primary', 'list-group-item-primary', 'list-group-item-primary', 'list-group-item-danger', 'list-group-item-danger', 'list-group-item-warning']
			];
		}
		else if(mode == "Pick x2, Pick x2 then Random")
		{
			var add_preview = [
				['Team 1 PICK', 'Team 2 PICK', 'Team 1 PICK', 'Team 2 PICK', 'RANDOM'],
				['list-group-item-primary', 'list-group-item-primary', 'list-group-item-primary', 'list-group-item-primary', 'list-group-item-warning']
			];
		}
		else if(mode == "Pick, Ban, Pick, Ban, Pick, Pick then Random")
		{
			var add_preview = [
				['Team 1 PICK', 'Team 2 BAN', 'Team 1 PICK', 'Team 2 BAN', 'Team 1 PICK', 'Team 2 PICK', 'RANDOM'],
				['list-group-item-primary', 'list-group-item-danger', 'list-group-item-primary', 'list-group-item-danger', 'list-group-item-primary', 'list-group-item-primary', 'list-group-item-warning']
			];
		}
		else if(mode == "Ban x2, Pick x2, Ban x2 then Random")
		{
			var add_preview = [
				['Team 1 BAN', 'Team 2 BAN', 'Team 1 PICK', 'Team 2 PICK', 'Team 1 BAN', 'Team 2 BAN', 'RANDOM'],
				['list-group-item-danger', 'list-group-item-danger', 'list-group-item-primary', 'list-group-item-primary', 'list-group-item-danger', 'list-group-item-danger', 'list-group-item-warning']
			];
		}
		else if(mode == "Ban x2, Ban x2, Pick x2 then Random")
		{
			var add_preview = [
				['Team 1 BAN', 'Team 2 BAN', 'Team 1 BAN', 'Team 2 BAN', 'Team 1 PICK', 'Team 2 PICK', 'RANDOM'],
				['list-group-item-danger', 'list-group-item-danger', 'list-group-item-danger', 'list-group-item-danger', 'list-group-item-primary', 'list-group-item-primary', 'list-group-item-warning']
			];
		}
		else if(mode == "Ban x2, Ban x2 then Random")
		{
			var add_preview = [
				['Team 1 BAN', 'Team 2 BAN', 'Team 1 BAN', 'Team 2 BAN', 'RANDOM'],
				['list-group-item-danger', 'list-group-item-danger', 'list-group-item-danger', 'list-group-item-danger', 'list-group-item-warning']
			];
			if(format == "Best of 2")
			{
				add_preview[0].push('RANDOM');
				add_preview[1].push('list-group-item-warning');
			}
			if(format == "Best of 3")
			{
				add_preview[0].push('RANDOM');
				add_preview[1].push('list-group-item-warning');
				add_preview[0].push('RANDOM');
				add_preview[1].push('list-group-item-warning');
			}
		}
		else if(mode == "Ban x2, Ban x2 then Pick x2")
		{
			var add_preview = [
				['Team 1 BAN', 'Team 2 BAN', 'Team 1 BAN', 'Team 2 BAN', 'Team 1 PICK', 'Team 2 PICK'],
				['list-group-item-danger', 'list-group-item-danger', 'list-group-item-danger', 'list-group-item-danger', 'list-group-item-primary', 'list-group-item-primary']
			];
		}
		else if(mode == "Ban x2, Pick x2 then Random")
		{
			var add_preview = [
				['Team 1 BAN', 'Team 2 BAN',  'Team 1 PICK', 'Team 2 PICK', 'RANDOM'],
				['list-group-item-danger', 'list-group-item-danger', 'list-group-item-primary', 'list-group-item-primary', 'list-group-item-warning']
			];
		}
		else if(mode == "Ban x2 then Random")
		{
			var add_preview = [
				['Team 1 BAN', 'Team 2 BAN', 'RANDOM'],
				['list-group-item-danger', 'list-group-item-danger', 'list-group-item-warning']
			];
			if(format == "Best of 2")
			{
				add_preview[0].push('RANDOM');
				add_preview[1].push('list-group-item-warning');
			}
		}
		else if(mode == "Pick x2")
		{
			var add_preview = [
				['Team 1 PICK', 'Team 2 PICK'],
				['list-group-item-primary', 'list-group-item-primary']
			];
		}
		else if(mode == "Pick x2 then Random")
		{
			var add_preview = [
				['Team 1 PICK', 'Team 2 PICK', 'RANDOM'],
				['list-group-item-primary', 'list-group-item-primary', 'list-group-item-warning']
			];
		}
		else if(mode == "Random")
		{
			var add_preview = [
				['RANDOM'],
				['list-group-item-warning']
			];
			if(format == "Best of 2")
			{
				add_preview[0].push('RANDOM');
				add_preview[1].push('list-group-item-warning');
			}
			if(format == "Best of 3")
			{
				add_preview[0].push('RANDOM');
				add_preview[1].push('list-group-item-warning');
				add_preview[0].push('RANDOM');
				add_preview[1].push('list-group-item-warning');
			}
			if(format == "Best of 5")
			{
				add_preview[0].push('RANDOM');
				add_preview[1].push('list-group-item-warning');
				add_preview[0].push('RANDOM');
				add_preview[1].push('list-group-item-warning');
				add_preview[0].push('RANDOM');
				add_preview[1].push('list-group-item-warning');
				add_preview[0].push('RANDOM');
				add_preview[1].push('list-group-item-warning');
			}
		}
		else
		{
			location.reload(true);
		}
		$("#preview").empty();
		for(var i=0; i<add_preview[0].length; i++){
			$('#preview')
				.append($("<li class='list-group-item font-weight-bold'></li>")
				.addClass(add_preview[1][i])
				.text(add_preview[0][i]));
		}
	});
	$(".map").click(function() {
		var picture = $(this);
		var input_hidden = "#" + picture.attr('id') + "_hidden";

		if(picture.hasClass("veto_map"))
		{
			picture.removeClass("veto_map").addClass("veto_ban");
			$(input_hidden).val("no");
		}
		else
		{
			picture.removeClass("veto_ban").addClass("veto_map");
			$(input_hidden).val("yes");
		}
		
		var map_number = $(".veto_map").length;
		
		if(map_number != 7)
		{
			$("#error").html("You have to choose at least 7 maps !");
			$("#button_veto").prop('disabled', true);
		}
		else
		{
			$("#error").empty();
			$("#button_veto").prop('disabled', false);
		}
		
	});
});