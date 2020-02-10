
var ERROR_CL = 'alert alert-danger';
var SUCCESS_CL = 'alert alert-success';

$(function () {
    $('#message-displayer').hide();
});

function saveVars() {

    if (!validVarsName()) {
        msg('Nom(s) de variable(s) vide(s) ou non valide(s)', ERROR_CL);
        return;
    }


    let vars_div = $('#vars');
    let datasources_variables_definition = [];

    vars_div.children().each(function (index) {
        let var_row = $(this);
        datasources_variables_definition.push(
            {
                'id': var_row.find('span').text(),
                'name': var_row.find('.var-name').val(),
                'description': var_row.find('.var-description').val(),
            }
        );
    });



    buttons(false);

    $.ajax({
        type: 'POST',
        data: {
            '_token': $('meta[name=csrf-token]').attr('content'),
            'datasources_variables_definition': datasources_variables_definition,
        },
        success: function (data, statut, xhr) {
            msg(xhr.responseJSON.msg, SUCCESS_CL);
        },

        error: function (data, statut, xhr) {
            msg(xhr.responseJSON.msg, ERROR_CL);
        },

        complete: function (data) {
            if (data.responseJSON.needRefresh) {
                document.location.reload(true);
            } else {
                buttons(true);
            }
        }
    });
}


function saveDataDefault(ID) {

    let id_database = $('#databaseSelected-' + ID).val();
    let query = $('#queryTextArea-' + ID).val();

    let datasources_default = {
        'id_database' : id_database,
        'id' : ID,
        //'table_associated' : ,
        'query' : query ,
    };

    buttons(false);

    $.ajax({
        type: 'POST',
        data: {
            '_token': $('meta[name=csrf-token]').attr('content'),
            'datasources_default': datasources_default,
        },
        success: function (data, statut, xhr) {
            msg(xhr.responseJSON.msg, SUCCESS_CL);
        },

        error: function (data, statut, xhr) {
            msg(xhr.responseJSON.msg, ERROR_CL);
        },

        complete: function (data) {
            if (data.responseJSON.needRefresh) {
                document.location.reload(true);
            } else {
                buttons(true);
            }
        }
    });
}

function newVar() {

    if (!validVarsName()) {
        msg('Nom de variables vides ou non valides', ERROR_CL);
        return;
    }

    let var_template_html = `
        <div class="row" >
            <div class="col col-sm-4">
                <input type="text" class="form-control var-name" placeholder="Nom" value="">
            </div>
            <div class="col col-sm-7">
                <input type="text" class="form-control var-description" placeholder="Description" value="">
            </div>
             <div class="col col-sm-1">
                <button type="button" class="btn btn-danger" onClick="deleteVar(null, this)">X</button>
            </div>
        </div>`;

    $('#vars').append(var_template_html);
}


function deleteVar(ID, el) {
    if (ID == null) {
        $(el).closest('.row').remove();
        return;
    }

    $.ajax({
        type: 'POST',
        data: {
            '_token': $('meta[name=csrf-token]').attr('content'),
            'var_id_to_delete': ID,
        },
        success: function (data, statut, xhr) {
            $(el).closest('.row').remove();
        },

        error: function (data, statut, xhr) {
            msg(xhr.responseJSON.msg, ERROR_CL);
        },

        complete: function (data) {
        }
    });

}


function buttons(bool) {
    $('html').find(':button').prop('disabled', !bool); // Disable-Enable all the buttons
}

function validVarsName() {
    let valid = true;
    $('#vars').children().each(function (index) {
        let var_name = $(this).find('.var-name');
        
        if (var_name.val().length === 0) {
            valid = false;
        } else {
            verifyAndUpdateVarsName(var_name);
        }
    });

    return valid;
}


function verifyAndUpdateVarsName(el) {
    let origin = el.val();
    el.val(origin.replace(/\s/g, '_'));
}

function msg(message, classes) {
    $('#message-displayer').finish();
    $('#message').text(message);
    $('#message-displayer').show().delay(5000).fadeOut();
    $('#inner-message').removeClass();
    $('#inner-message').addClass(classes);
}
