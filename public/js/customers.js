var currentIdDeletion = 0;
var table


$(document).ready( function () {
	table = $('#table_id').DataTable( {
        dom: 'Bfrtip',

        buttons: [],
        "oLanguage": {
            "sSearch": "Filtrer :",
            "sLengthMenu": "Affichage de _MENU_ résultats",
            "zeroRecords": "Aucun client trouvé",
            "info": "Page _PAGE_ sur _PAGES_",
            "infoEmpty": "Aucun client trouvé",
            "infoFiltered": "(filtré parmi les _MAX_ clients)"
        },
        "language": {
            "paginate": {
                "previous": "Précédent",
                "next": "Suivant"
            }
        },
        "columns": [
            { "width": "80%" },
            { "width": "10%", "orderable" : false },
        ],
        "initComplete": function (settings, json) {
            $('#loading').hide();
            $('#table_id').css('opacity', '1');
            $('#table_id').children().css('opacity','1');
            $('#table_id').show();
        },
        
		"pageLength": 50,
		"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100,"All"]],
	} );


});


