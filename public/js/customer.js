$(function() {
    //Hide collapsed div by default
	let numberOfCollapseDiv = 4;
    for(let i=1; i<=numberOfCollapseDiv ; i++) {
        $( '#collapseDiv' + i ).collapse();
    }

});

function updateCustomer(event) {
    event.preventDefault(); //cancel href
    $('#update-customer-form').submit();
    $('#loading-gif-update').css("visibility", "visible");
    $('#update-button').addClass("not-active");
    $.ajax({
        type: 'POST',
        data: {
            '_token': $('meta[name=csrf-token]').attr('content'),
        },
        success: function (data, statut) {
            location.reload();
        },
        error: function (resultat, statut, xhr) {
            alert(xhr);
        },
        complete: function () {
            $('#loading-gif-update').css("visibility", "hidden");
            $('#update-button').removeClass("not-active");
        }
    });
}

var tableAssociated;
function openModal(table_name) {
    table_name = table_name.replace(/\s/g, '');
    tableAssociated = table_name;

    let table = datasources[table_name];
    $('#attributes-list').html('');
    for (var key in table) {
        if (table[key].localeCompare("id") == 0) continue;
        $("#attributes-list").append("<li>"+ table[key] +"</li>");
    }
    $('#queryTextArea').val('');
}

function saveDatasource() {
    let databaseId = $('#databaseSelected').val();
    let query = $('#queryTextArea').val();
    var data = {
        'databaseId': databaseId,
        'tableAssociated' : tableAssociated,
        'query' : query,
    };
    console.log(data);

    $.ajax({
        type: 'POST',
        data: {
            '_token': $('meta[name=csrf-token]').attr('content'),
            'datasource': data,
        },
        success: function (data, statut) {
            console.log(data);
        },

        error: function (data, statut, xhr) {
            console.log(data);
        },

        complete: function (data) {
            
        }
    });


}
