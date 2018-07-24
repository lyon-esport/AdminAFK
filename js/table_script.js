$(document).ready(function() {
	$('#users').DataTable( {
	"oSearch": { "bSmart": false, "bRegex": true }
	} );
	$('#log').DataTable( {
	"oSearch": { "bSmart": false, "bRegex": true }
	} );
} );