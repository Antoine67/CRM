
var db;
var id;
var type = 0; //0 = edit, 1 = new


$(function () {
    $(".db-item").click(function () {
        $('#msg-displayer').hide();
        type = 0;
        id = $(this).attr('id').substring("db-item-".length);

        databases.forEach((item, index) => {
            if (item.id == id) {
                db = item;
                return;
            }
        })


        $('#dbModalTitle').text(db.name);
        $('#dbName').val(db.name);
        $('#dbHost').val(db.host);
        $('#dbPort').val(db.port);
        $('#dbUser').val(db.username);
        $('#dbPassword').val(db.password);
        $('#driver-' + db.driver).prop('selected', true);
        $('#dbModal').modal('show');
       
    });
    $('#dbModal').modal('hide');
});

function testConnection() {

    if (!checkForm()) return;

    buttons(false);
    let db_props = {
        'name': $('#dbName').val(),
        'host': $('#dbHost').val(),
        'port': $('#dbPort').val(),
        'username': $('#dbUser').val(),
        'password': $('#dbPassword').val(),
        'driver': $('#dbDriver').val(),
    };


    $.ajax({
        //url: '',
        type: 'POST',
        data: {
            '_token': $('meta[name=csrf-token]').attr('content'),
            'db': db_props,
        },
        success: function (data, statut) {
            $('#msg-txt').text(data['msg']);
            $('#msg-displayer').removeClass();
            $('#msg-displayer').addClass("alert alert-success fade show");
            $('#msg-displayer').show();

            $('#' + id + '-state').removeClass();
            $('#' + id + '-state').addClass('badge badge-success badge-pill');
            $('#' + id + '-state').text('Connecté');
        },

        error: function (data, statut, xhr) {
            $('#msg-txt').text(data.responseJSON.msg);
            $('#msg-displayer').removeClass();
            $('#msg-displayer').addClass("alert alert-danger fade show");
            $('#msg-displayer').show();

            $('#' + id + '-state').removeClass();
            $('#' + id + '-state').addClass('badge badge-danger badge-pill');
            $('#' + id + '-state').text('Non connecté');
        },

        complete: function (data) {
            buttons(true);
        }
    });
}

function save() {

    if (!checkForm()) return;


    buttons(false);

    let db_props = {
        'name': $('#dbName').val(),
        'host': $('#dbHost').val(),
        'port': $('#dbPort').val(),
        'username': $('#dbUser').val(),
        'password': $('#dbPassword').val(),
        'driver': $('#dbDriver').val(),
    };
    let typeStr;
    if (type > 0) { //0 = edit, 1 = new
        typeStr = "new";
    } else {
        typeStr = "edit";
    }


    $.ajax({
        //url: '',
        type: 'POST',
        data: {
            '_token': $('meta[name=csrf-token]').attr('content'),
            'db_edit_create': db_props,
            'type': typeStr,
        },
        success: function (data, statut) {
            $('#msg-txt').text(data['msg']);
            $('#msg-displayer').removeClass();
            $('#msg-displayer').addClass("alert alert-success fade show");
            $('#msg-displayer').show();
            $('#dbModal').modal('hide');
            document.location.reload(true);
        },

        error: function (data, statut, xhr) {
            $('#msg-txt').text(data.responseJSON.msg);
            $('#msg-displayer').removeClass();
            $('#msg-displayer').addClass("alert alert-danger fade show");
            $('#msg-displayer').show();

            $('#' + id + '-state').removeClass();
            $('#' + id + '-state').addClass('badge badge-danger badge-pill');
            $('#' + id + '-state').text('Non connecté');
        },

        complete: function (data) {
            buttons(true);
        }
    });
}

function checkForm() {
    $('#msg-txt').text('');
    if ($('#dbName').val() && $('#dbHost').val() && $('#dbDriver').val() && $('#dbPort').val()) {
        $('#msg-displayer').hide();
        return true;
    }

    $('#msg-txt').text("Un champ requis est vide");
    $('#msg-displayer').removeClass();
    $('#msg-displayer').addClass("alert alert-danger fade show");
    $('#msg-displayer').show();

    return false;
}

function addDatabase() {
    type = 1;
    $('#msg-displayer').hide();
    $('#dbModalTitle').text("Nouvelle connexion");
    $('#dbName').val('');
    $('#dbHost').val('');
    $('#dbPort').val('');
    $('#dbUser').val('');
    $('#dbPassword').val('');

    $("#dbDriver").val($("#dbDriver option:first").val());

    $('#dbModal').modal('show');
}

function buttons(bool) {
    $('#dbModal').find(':button').prop('disabled', !bool); // Disable-Enable all the buttons
}
