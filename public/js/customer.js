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
    $.ajax({
        type: 'POST',
        data: {
            '_token': $('meta[name=csrf-token]').attr('content'),
        },
        success: function (data, statut) {
            if (data['success']) {
                alert('SUCESS ; updated');
            } else {
                alert('ERROR ; updated');
            }
            
        },

        error: function (resultat, statut, xhr) {
            alert(xhr);
        }
    });
}
