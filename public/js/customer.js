var current_default_usage;


$(function () {
    //Hide collapsed div by default
	let numberOfCollapseDiv = 4;
    for(let i=1; i<=numberOfCollapseDiv ; i++) {
        $('#collapseDiv' + i).collapse();
    }

    $('#msg-displayer').hide();

    $('#custom-selector').change(function () {
        $('.ds').hide();
        $('#msg-displayer').hide();
        $('#' + $('#custom-selector').val()).show();
    });

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
            location.reload();
        },
        complete: function () {
            $('#loading-gif-update').css("visibility", "hidden");
            $('#update-button').removeClass("not-active");
        }
    });
}

var tableAssociated;
function openModal(table_name, el) {
    $('#msg-displayer').hide();


    
    default_usage.forEach((item, index) => {
        if (item.table_associated == table_name) {
            current_default_usage = item.default_usage;
            if (item.default_usage > 0) {
                $('#custom-selector').val('default');
            } else {
                $('#custom-selector').val('custom');
            }
        }
    });
    $('.ds').hide();
    $('#' + $('#custom-selector').val()).show();
    




    let query = $(el).find(".query-text").text();
    table_name = table_name.replace(/\s/g, '');
    tableAssociated = table_name;
    $('#queryTextArea').val(query);

    let table = datasources[table_name];
    $('#attributes-list').html('');
    for (var key in table) {
        if (table[key].localeCompare("id") == 0) continue;
        $("#attributes-list").append("<li>"+ table[key] +"</li>");
    }
}

function saveDatasource() {

    if ($('#custom-selector').val() == "default") {
        buttons(false);
        $.ajax({
            type: 'POST',
            data: {
                '_token': $('meta[name=csrf-token]').attr('content'),
                'default_usage_table_name': tableAssociated,
            },
            success: function (data, statut) {
                $('#msg-txt').text(data.msg);
                $('#msg-displayer').removeClass();
                $('#msg-displayer').addClass("alert alert-success fade show");
                $('#msg-displayer').show();
            },

            error: function (data, statut, xhr) {
                $('#msg-txt').text(data.msg);
                $('#msg-displayer').removeClass();
                $('#msg-displayer').addClass("alert alert-danger fade show");
                $('#msg-displayer').show();
            },

            complete: function (data) {
                buttons(true);
            }
        });
        return;
    }


    buttons(false);
    let databaseId = $('#databaseSelected').val();
    let query = $('#queryTextArea').val();
    var data = {
        'databaseId': databaseId,
        'tableAssociated' : tableAssociated,
        'query' : query,
    };

    $.ajax({
        type: 'POST',
        data: {
            '_token': $('meta[name=csrf-token]').attr('content'),
            'datasource': data,
        },
        success: function (data, statut) {
            $('#msg-txt').text(data.msg);
            $('#msg-displayer').removeClass();
            $('#msg-displayer').addClass("alert alert-success fade show");
            $('#msg-displayer').show();
        },

        error: function (data, statut, xhr) {
            $('#msg-txt').text(data.msg);
            $('#msg-displayer').removeClass();
            $('#msg-displayer').addClass("alert alert-danger fade show");
            $('#msg-displayer').show();
        },

        complete: function (data) {
            buttons(true);
        }
    });


}

function buttons(bool) {
    $('html').find(':button').prop('disabled', !bool); // Disable-Enable all the buttons
}

