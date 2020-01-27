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
