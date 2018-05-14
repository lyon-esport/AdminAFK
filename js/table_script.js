$(document).ready(function() {
    $('#connect_teams').DataTable( {
	"oSearch": { "bSmart": false, "bRegex": true }
	} );
	$('#users').DataTable( {
	"oSearch": { "bSmart": false, "bRegex": true }
	} );
	$('#log').DataTable( {
	"oSearch": { "bSmart": false, "bRegex": true }
	} );
} );