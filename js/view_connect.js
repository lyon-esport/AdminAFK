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
  var connect_tab = $("#data");
  var timer_progressbar = $("#timer_progressbar");
  var value_search = $('.dataTables_filter input').val();
  var connect_save = $("#connect_save");

  $.ajax({
     url : 'data_connect.php',
     type : 'GET',
     dataType : 'html',
     success : function (code_html, statut){
       if(connect_save.val() != code_html)
       {
          connect_tab.html(code_html);
          connect_save.val(code_html);
          $('#connect_teams').DataTable( {
        	"oSearch": { "bSmart": false, "bRegex": true, "sSearch": value_search}
        	} );
        }
        timer_progressbar.css('width', '100%').attr('aria-valuenow', '100');
        timer_progressbar.html('10 s');
  	 }
  });
}

function update_progressbar(){
  var timer_progressbar = $("#timer_progressbar");
  var percent = 0;

  percent = timer_progressbar.attr('aria-valuenow') - 10;
  if(percent>0)
  {
    timer_progressbar.css('width', percent+'%').attr('aria-valuenow', percent);
    timer_progressbar.html(percent/10 + ' s');
  }
}

$(document).ready(function(){
   get_data();
   setInterval(get_data,10000);
   setInterval(update_progressbar,1000);
});
