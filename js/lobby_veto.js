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
function get_data(){
var id = $('#lobby_id').val();
var veto_span = $("#veto");
var veto_save = $("#veto_save");
$.ajax({
   url : 'data_veto.php',
   type : 'GET',
   data : 'id=' + id,
   dataType : 'html',
   success : function (code_html, statut){
	   if(veto_save.val() != code_html)
	   {
			$("#exampleModal").modal('hide');
			$('body').removeClass('modal-open');
			$('.modal-backdrop').remove();
			veto_span.html(code_html);
			veto_save.val(code_html);
	   }
   }
});
}
$(document).ready(function(){
   get_data();
   setInterval(get_data,1000);
   $(".map").click(function() {
		var id = $('#lobby_id').val();
		var map = $(this).attr('id');
		$.ajax({
		   url : '../traitement/veto_map.php',
		   type : 'POST',
		   data : 'id=' + id + "&map=" + map,
		   success : function(code_html, statut){
			   $("#exampleModal").modal('hide');
			   $('body').removeClass('modal-open');
			   $('.modal-backdrop').remove();
		   }
		});
	});	
});

function show()
{
	var copyText = document.getElementById("copy");
	var button_show = document.getElementById("button_show");

	if(copyText.type === "text")
	{
		copyText.type = "password";
		button_show.innerHTML= "Show";
	}
	else
	{
		copyText.type = "text";
		button_show.innerHTML= "Hide";
	}
}

function copy() {
  	var copyText = document.getElementById("copy");
	var buttonCoppy = document.getElementById("button_copy");

	var beforeChangeCopy = copyText.type === "text";

	buttonCoppy.classList.remove("btn-outline-secondary");
	buttonCoppy.classList.add("btn-outline-success");

	copyText.type = "text";

  	copyText.select();
  	document.execCommand("copy");

	beforeChangeCopy ? copyText.type = "text" : copyText.type = "password";
  
  	TimeOut = setTimeout(changeColorBack, 1000);
}

function changeColorBack() {
	var buttonCoppy = document.getElementById("button_copy");
	buttonCoppy.classList.add("btn-outline-secondary");
	buttonCoppy.classList.remove("btn-outline-success");
}